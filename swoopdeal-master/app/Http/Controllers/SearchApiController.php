<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Categories;

setlocale(LC_MONETARY, 'en_US');

class SearchApiController extends Controller {

    public function api($search) {

    }

    public function searchApiCategories($search) {
        $matches = [];
        $childCatMatches = [];
        $childCats = [];

        foreach ($this->categories as $key => $category) {
            $tempId = $category['id'];
            foreach ($category['children'] as $subCat) {
                $childCats[] = ['cat_id' => "{$tempId}/{$subCat['id']}", 'cat_name' => $subCat['title']];
            }
        }

        foreach ($childCats as $index => $childCat) {
            if (preg_match("/{$search}/i", $childCat['cat_name'])) {
                $childCatMatches[] = ['cat_id' => $childCat['cat_id'], 'cat_name' => $childCat['cat_name']];
            }
        }


        foreach ($this->categories as $key => $cat) {
            foreach ($cat as $index => $category) {
                if ($index == 'title') {
                    if (preg_match("/{$search}/i", $category)) {
                        $matches[] = ['cat_id' => $cat['id'], 'cat_name' => $category];
                    }
                }
            }

        }

        $matches = array_merge($matches, $childCatMatches);

        return response()->json($matches);

    }

    public function searchApiProducts ($search) {
        $results = $this->provider->search($search);

        foreach($results as $result) {
            $matches[] = ['id' => $result->getID(), 'prod_name' => $result->getTitle()];
        }

        return response()->json($matches);

    }
    

}