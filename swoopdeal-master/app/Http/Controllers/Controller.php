<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Adrenalads\CommerceApi\Taxonomy;
use Adrenalads\CommerceApi\Manager;
use Wpml\Post as Post;
use App\Models\AdrenPost;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const CACHED_MINUTES = 1440;

    protected $provider = '';
    protected $Manager;
    protected $categories = [];
    protected $locale;

    protected $featureProducts = [];
    public $minPrice = 12;
    public $maxPrice = 995;
    protected $brandIdValue = 259;
    protected $storeIdValue = 296935;
    protected $locales = [
        'de' => ['lang_format' => 'de_DE.UTF-8', 'currency_format' => '%(#1n', 'prefix' => '€'],
        'fr' => ['lang_format' => 'fr_FR.UTF-8', 'currency_format' => '%(#1n', 'prefix' => '€'],
        'gb' => ['lang_format' => 'en_GB.UTF-8', 'currency_format' => '%n', 'prefix' => '\u20AC'],
        'us' => ['lang_format' => 'us_US.UTF-8', 'currency_format' => '$%i', 'prefix' => '$']
    ];

    public function __construct() {
        $this->Manager = new Manager();
        $this->provider = $this->Manager->getProvider();

        $this->locale = env('APP_LOCALE', 'us');

        setlocale(LC_MONETARY, $this->locales[$this->locale]);

    }

/*    public function recentArticles() {
        $AdrenPosts = new AdrenPost();
        $Posts = $AdrenPosts->getTranslated($this->locale, 5)->orderBy('ID', 'desc')->get();

        $recentArticles = collect($Posts)->map(function($item) {
            $date = $item->post_date->format('F jS, Y');
            return [
                'post_id' => $item->ID,
                'post_date' => $date,
                'title' => $item->post_title,
                'slug' => $item->slug
            ];
        });

        return $recentArticles;
    }

    public function featuredArticles() {
        $AdrenPosts = new AdrenPost();
        $Posts = $AdrenPosts->getTranslated($this->locale, 4)->orderBy('ID', 'desc')->get();

        $featuredArticles = collect($Posts)->map(function($item) {
            $date = $item->post_date->format('F jS, Y');
            return [
                'post_id' => $item->ID,
                'post_date' => $date,
                'title' => $item->post_title,
                'slug' => $item->slug
            ];
        });

        return $featuredArticles;
    }*/

    public function featureProducts($allProducts = null) {

        if(!is_array($allProducts)) {
            $this->featureProducts = array_slice($allProducts->items(), 3, 9);
        }

    }

}
