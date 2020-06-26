<?php
namespace App\Http\Validation;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Country;

class ValidateCountryCode implements Rule {

    protected $message = 'The selected country is invalid.';

    public function passes($attribute, $value) {
        return $this->validateCampaignCountry($value);
    }

    public function message() {
        return $this->message;
    }

    protected function validateCampaignCountry($value) {
        return in_array($value, ['US', 'ALL', 'INTL'])
            || in_array($value, array_keys(Country::all()));
    }


}