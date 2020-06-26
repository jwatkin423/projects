<?php
namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Merchants;
use App\Models\Offers;
use Illuminate\Http\Request;


class HomeController extends Controller {

    public function about() {
        $minPrice = $this->minPrice;
        $maxPrice = $this->maxPrice;

        $message = false;
        $allProducts = $this->provider->getFeaturedProducts();
        $this->featureProducts($allProducts);
        $featuredProducts = $this->featureProducts;

        $q = null;

//        $recentArticles = $this->recentArticles();

        return view('about')
            ->with('q', $q)
            ->with('aboutPage', true)
            ->with('title', 'Home')
            ->with('section', 'products')
            ->with('breadcrumbs', [])
            ->with('singlePost', false)
            ->with('allProducts', $allProducts)
            ->with('brands', [])
            ->with('stores', [])
            ->with('minPrice', $minPrice)
            ->with('maxPrice', $maxPrice)
            ->with('featuredProducts', $featuredProducts)
//            ->with('featuredArticles', $this->featuredArticles())
//            ->with('recentArticles', $recentArticles)
            ->with('pagination', false)
            ->with('message', $message)
            ->with('categories', $this->categories)
            ->with('section', 'about');
    }

    public function terms_privacy(Request $request) {
        $section = $request->segment(2);
        $message = false;
        $allProducts = $this->provider->getFeaturedProducts();
        $this->featureProducts($allProducts);
        $featuredProducts = $this->featureProducts;
        $q = null;

        $minPrice = $this->minPrice;
        $maxPrice = $this->maxPrice;

//        $recentArticles = $this->recentArticles();

        return view('termsprivacy')
            ->with('q', $q)
            ->with('aboutPage', true)
            ->with('title', 'Home')
            ->with('section', 'products')
            ->with('brands', [])
            ->with('stores', [])
            ->with('minPrice', $minPrice)
            ->with('maxPrice', $maxPrice)
            ->with('breadcrumbs', [])
            ->with('singlePost', false)
            ->with('allProducts', $allProducts)
            ->with('featuredProducts', $featuredProducts)
//            ->with('featuredArticles', $this->featuredArticles())
//            ->with('recentArticles', $recentArticles)
            ->with('pagination', false)
            ->with('message', $message)
            ->with('categories', $this->categories)
            ->with('section', $section);
    }

}