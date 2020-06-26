<?php

namespace App\Http\Controllers;

use App\Http\Validation\ValidateCountryCode;
use App\Http\Validation\ValidateGreaterThanIf;
use App\Models\Campaign;
use App\Models\Advertiser;
use Illuminate\Http\Request;

class CampaignsController extends BaseController {

    public function index(Request $request) {
        $data['title'] = 'Campaigns';

        $data['with_inactive'] = $with_inactive = $request->get('with_inactive');

        // get campaigns
        if ($with_inactive) {
            $campaigns = Campaign::all();
        } else {
            $campaigns = Campaign::withoutInactive()->get();
        }

        $data['campaigns'] = $campaigns;
        $data['statuses'] = [
            'active' => 'btn-success',
            'paused' => 'btn-warning',
            'inactive' => 'btn-default'
        ];

        return view('campaigns.index', $data);
    }

    // display blank form
    public function create() {
        $campaign = new Campaign();

        $data['title'] = 'Create Campaign';
        $data['campaign'] = $campaign;
        $data['route'] = 'campaigns.create';
        $data['advertisers'] = $this->advertiserSelect(Advertiser::getAdvertiserLists());
        $data['hours'] = $this->hoursDisplay();

        return view('campaigns.edit', $data);
    }

    // show campaign to edit
    public function edit($campaign_name) {
        $campaign_parts = explode('_', $campaign_name);
        $Campaign = Campaign::where('adv_code', '=', $campaign_parts[0])->where('campaign_code', $campaign_parts[1])->first();

        $data['title'] = 'Edit "' . $Campaign->getName() . '" campaign';
        $data['campaign'] = $Campaign;
        $data['advertisers'] = $this->advertiserSelect(Advertiser::getAdvertiserLists());
        $data['hours'] = $this->hoursDisplay();

        return view('campaigns.edit', $data);
    }

    // create new campaign
    public function store(Request $request) {

        $est_rev_type = $request->get('est_rev_type');
        $est_rev_multiplier = $request->get('est_rev_multiplier');
        $est_rpc = $request->get('est_rpc');

        $this->validate($request, [
            'est_rev_type' => 'required|in:rpc,rev_multiplier',
            'est_rev_multiplier' => 'required_if:est_rev_type,==,rev_multiplier|numeric|nullable',
            'est_rpc' => 'required_if:est_rev_type,==,rpc|numeric|min:0|nullable',
            'max_bid_type.0' => 'in:bid,multiplier,bid-multiplier',
            'max_bid_type.1' => 'in:bid,multiplier,bid-multiplier',
            'max_bid' => 'required_if:max_bid_multiplier,0|numeric|min:0|nullable',
            'max_bid_multiplier' => 'required_if:max_bid,0|numeric|nullable',
            'budget' => 'required|numeric|min:0',
            'status' => 'in:active,paused,inactive',
            'bid_key' => 'nullable|min:0|max:255'
        ]);

        $campaign = new Campaign($request->except('token'));

        if ($campaign->save()) {
            //Populating properties from DB and create Alert
            Campaign::createAlert($campaign->getName(), false, $this->_replaceKeywordsWithCount($campaign->find($campaign->id)->attrsForAlert()));
            return redirect()->action('CampaignsController@index')->with('message', "Campaign {$campaign->getName()} successfully created.");
        }
    }

    // update campaign
    public function update(Request $request) {
        $campaign_request = $request->all(['adv_key', 'budget_max']);
        // campaign components
        $adv_key = $campaign_request['adv_key'];
        unset($campaign_request['adv_key']);
        list ($adv_code, $campaign_code) = explode('_', $adv_key);

        // row and field to set once the ajax call has been successful

        // get campaign by adv_code and campaign_code
        $campaign = Campaign::where('adv_code', '=', $adv_code)
                            ->where('campaign_code', '=', $campaign_code)
                            ->first();

        // update campaign with new settings
        $campaign->fill($campaign_request);
        $dirty = $campaign->getDirty();
        $original = $campaign->getOriginal();

        if ($campaign->save()) {
            Campaign::createAlert($campaign->getName(), true, $this->_replaceKeywordsWithCount($dirty), $this->_replaceKeywordsWithCount($original));
            $message = "Campaign {$campaign->getName()} successfully updated.";
            return response()->json(['status' => 'success', 'message' => $message]);
        } else {
            return response()->json(['status' => 'error']);
        }

    }

    public function setStatus($campaign, $status) {
        $campaignParts = explode('_', $campaign);
        $adv_code = $campaignParts[0];
        $campaign_code = $campaignParts[1];

        $Campaign = Campaign::where('adv_code', '=', $adv_code)
                 ->where('campaign_code', '=', $campaign_code)
                 ->first();

        $Campaign->status = $status;
        $dirty = $Campaign->getDirty();
        $original = $Campaign->getOriginal();

        if ($Campaign->save()) {
            Campaign::createAlert($Campaign->getName(), true, $dirty, $original);
            return redirect()->action('CampaignsController@index')
                             ->with('message', "Campaign {$Campaign->getName()} successfully updated.");
        }

    }

    private function _replaceKeywordsWithCount($attrs) {
        if (isset($attrs['keywords'])) {
            $attrs['keywords'] = count(explode(",", $attrs['keywords']));
        }
        return $attrs;
    }

    private function hoursDisplay() {
        $hours = [
            0 => 'All hours'
        ];

        for ($i = 1; $i < 24; $i++) {
            $hours[$i] = $i . ':00';
        }

        return $hours;
    }

    private function advertiserSelect($advertisers) {

        $temp = [];

        foreach ($advertisers as $key => $value) {

            $pair = $advertisers[$key];
            $temp[$key] = $pair[$key];

        }

        return $temp;

    }
}
