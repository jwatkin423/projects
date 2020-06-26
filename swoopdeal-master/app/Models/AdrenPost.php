<?php
namespace App\Models;

use Wpml\Post as WP;
use Wpml\Translation\Translation;

class AdrenPost extends WP {

    protected $connection = 'wordpress';

    /**
     * @param $locale
     * @param $limit
     *
     * @return $Posts
     */
    public function getTranslated($locale, $limit = 5) {

        $in = Translation::select('element_id')->where('language_code', '=', $locale)
                                      ->where('element_type', '=', 'post_post')
                                      ->orderBy('element_id', 'DESC')
                                      ->limit($limit)
                                      ->get()->toArray();

        $Post = AdrenPost::whereIn('ID', $in);

        return $Post;
    }

}