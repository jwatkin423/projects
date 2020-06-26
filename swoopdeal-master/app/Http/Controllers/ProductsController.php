<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adrenalads\CommerceApi\CategoryOptions;
use Adrenalads\CommerceApi\Taxonomy;
use Illuminate\Support\Facades\Cache;
use Adrenalads\CommerceApi\Manager;

class ProductsController extends Controller {

    private $symbols = ['$', '€', '£'];

    public function __construct($processCats = 0) {
        parent::__construct();

        if (!$processCats) {
            if(!Cache::has('categories')) {

                $this->categories = new Taxonomy($this->provider->getCategories());

                $categories = $this->categories->getRawCategories();

                usort($categories, function($a, $b) {
                    return $a['title'] <=> $b['title'];
                });

                foreach ($categories as $key => $category) {

                    if (!count($category['children'])) {
                        unset($categories[$key]);
                    }

                }

                $this->categories = $categories;
                Cache::put('categories', $this->categories, self::CACHED_MINUTES);
            } else {

                if (is_array(Cache::get('categories'))) {
                    $this->categories = Cache::get('categories');
                } else {
                    $this->categories = json_decode(Cache::get('categories', $this->categories, self::CACHED_MINUTES), true);
                }

            }
        }

    }

    /**
     * @return mixed
     */
    public function index() {
        $message = false;

        // clear all sessions
        session()->forget('q');
        session()->forget('stores');
        session()->forget('brands');
        session()->forget('ps_categories');
        session()->forget('checkedStoresIds');
        session()->forget('checkedBrandsIds');

        if (empty($this->featureProducts)) {
            $allProducts = $this->provider->getFeaturedProducts();
            $this->featureProducts($allProducts);
            $featuredProducts = $this->featureProducts;
        }


        $q = null;

//        $recentArticles = $this->recentArticles();

        return view('offers.index')
            ->with('q', $q)
            ->with('title', 'Swoopdeal')
            ->with('section', 'products')
            ->with('brands', [])
            ->with('stores', [])
            ->with('minPrice', false)
            ->with('maxPrice', false)
            ->with('breadcrumbs', [])
            ->with('singlePost', false)
            ->with('allProducts', $allProducts)
            ->with('featuredProducts', $featuredProducts)
//            ->with('featuredArticles', $this->featuredArticles())
//            ->with('recentArticles', $recentArticles)
            ->with('pagination', false)
            ->with('message', $message)
            ->with('categories', $this->categories);
    }

    /**
     * @param Request $request
     * @param null $id
     * @param null $sub_id
     * @return mixed
     */
    public function category(Request $request, $id = null, $sub_id = null) {

        $title = 'Home';
        $q = 'cat_is_set';

        // clear all sessions
        session()->forget('q');
        session()->forget('checkedStoresIds');
        session()->forget('checkedBrandsIds');

        $message = false;
        if ($id == null) {
            $tempId = $request->get('cat_id') !== null ? $request->get('cat_id') : null;
            if ($tempId != null && str_contains($tempId, '/')) {
                $tempIds = explode('/', $tempId);
                $id = $tempIds[0];
                $sub_id = $tempIds[1];
            } else {
                $id = $tempId;
            }
        }

        $search_params = [
            'type' => 'max_price',
            'categoryId' => $id,
            'showAttributes' => 'true'
        ];

        $finite_id = $sub_id ? $sub_id : $id;
        $breadcrumbs = [['title' => 'home']];
        $options = new CategoryOptions($this->provider, $request->all());

        if (!session()->has('ps_categories')) {
            $taxonomy = new Taxonomy($this->provider->getCategories());
            if ($finite_id) {
                $breadcrumbs = array_map([$taxonomy, 'breadcrumbsToArray'], $taxonomy->getBreadcrumbs($finite_id));
                $taxonomy->setCurrent($finite_id);
                $title = $taxonomy->getCategory($finite_id)
                                  ->getTitle();
            }
        } else {
            $taxonomy = $request->session()->has('ps_categories');
            $ps_categories = array_column($request->session()->get('ps_categories'), 'title', 'id');
            $title = $ps_categories[$finite_id];
        }

        $allProducts = $this->provider->getFeaturedProducts();

        // set min price
        $minPrice = $request->input('minPrice', null);
        if ($minPrice) {
            $minPrice = (int)str_replace($this->symbols, '', $minPrice);
        }

        // set max price
        $maxPrice = $request->input('maxPrice', null);
        if ($maxPrice !== NULL) {
            $maxPrice = (int)str_replace($this->symbols, '', $maxPrice);
        }

        // set selected min amount
        $selectedAmount = $request->input('amount', null);
        if ($selectedAmount !== NULL) {
            $selectedAmount = (int)str_replace($this->symbols, '', $selectedAmount);
        }

        // set selected max amount
        $selectedAmount2 = $request->input('amount2', null);
        if ($selectedAmount2 !== NULL) {
            $selectedAmount2 = (int)str_replace($this->symbols, '', $selectedAmount2);
        }

        // set min price if selectedAmount is null
        if ($minPrice !== NULL && $selectedAmount === null) {
            $search_params['minPrice'] = $minPrice;
        }

        // set min price to selectedAmount is not null
        if (($selectedAmount >= $minPrice) && ($selectedAmount > 0)) {
            $search_params['minPrice'] = $selectedAmount;
        }

        // set max price if selectedAmount2 is null
        if ($maxPrice && $selectedAmount2 === null) {
            $search_params['maxPrice'] = $maxPrice;
        }

        // set max price to selectedAmount2 is not null
        if ($maxPrice > 0 && $selectedAmount2 <= $maxPrice) {
            $search_params['maxPrice'] = $selectedAmount2;
        }

        if(isset($search_params['maxPrice']) && !isset($search_params['minPrice'])) {
            $search_params['minPrice'] = 0;
        }

        if(isset($search_params['maxPrice']) && !isset($search_params['minPrice'])) {
            $search_params['minPrice'] = 0;
        }

        $page = $request->get('page', 1);

        $category_details = $this->provider->search($search_params, $page);

        if (isset($category_details['min_price']) && $category_details['min_price'] > 0) {
            $minPrice = ceil($category_details['min_price'] / 100);
            unset($category_details['min_price']);
        }

        if (isset($category_details['max_price']) && $category_details['max_price'] > 0) {
            $maxPrice = ceil($category_details['max_price'] / 100);
            unset($category_details['max_price']);
        }

        $prod_search_params = [
            'id' =>  $id,
            'subId' => $sub_id,
        ];

        if(isset($search_params['minPrice'])) {
            $minPrice = $search_params['minPrice'];
        }

        if (isset($search_params['maxPrice'])) {
            $maxPrice = $search_params['maxPrice'];
        }


        if ($minPrice) {
            $prod_search_params['minPrice'] = $minPrice;
        }

        if ($maxPrice) {
            $prod_search_params['maxPrice'] = $maxPrice;
        }

        if(!isset($prod_search_params['amount2'])) {
            $prod_search_params['amount2'] = $maxPrice;
        }

        if(!isset($prod_search_params['amount'])) {
            $prod_search_params['amount'] = $minPrice;
        }

        $products = $this->provider->getCategory($prod_search_params, $page);

        $products->setPath("/" . $request->path());
        $products->appends($options->getPaginatorParams());

        if ($title == 'Home') {
            $message = 'No search results were found';
        }

        if(session('ps_categories')) {
            $this->categories = session('ps_categories');
        }

        return view('offers.index')
            ->with('title', $title)
            ->with('q', $q)
            ->with('search', true)
            ->with('featuredProducts', $products)
            ->with('allProducts', $allProducts)
            ->with('brands', false)
            ->with('stores', false)
            ->with('main_id', $id)
            ->with('sub_id', $sub_id)
            ->with('checkedStoresIds', false)
            ->with('checkedBrandsIds', false)
            ->with('minPrice', $minPrice)
            ->with('maxPrice', (int)$maxPrice )
            ->with('selectedMinPrice', $selectedAmount)
            ->with('selectedMaxPrice', $selectedAmount2)
            ->with('catSearch', true)
//            ->with('recentArticles', $this->recentArticles())
            ->with('section', 'categories')
            ->with('singlePost', false)
//            ->with('featuredArticles', $this->featuredArticles())
            ->with('categories', $this->categories)
            ->with('options', $options)
            ->with('pagination', true)
            ->with('message', $message)
            ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(Request $request) {
        // search term
        $q = $request->input('q');

        $categories = $this->provider->getCategories(['keyword' => $q]);

        if (count($categories) > 1 ){
            asort($categories);
        }

        // set the initial search to q
        if(!session('q')) {
            session(['q' => $q]);
        } elseif (session('q') !== $q) {
            session()->forget('q');
            session()->forget('stores');
            session()->forget('brands');
            session()->forget('ps_categories');
            session()->forget('checkedStoresIds');
            session()->forget('checkedBrandsIds');
            session(['q' => $q]);
        }

        // set brand Ids
        $brandsIdsString = $request->input('brands', null);

        // set store Ids
        $storesIdsString = $request->input('stores', null);
        // message
        $message = 'Search results for: ' . $q;

        // search parameters
        $search_params['keyword'] = $q;

        // set min price
        $minPrice = $request->input('minPrice', null);
        if ($minPrice) {
            $minPrice = (int)str_replace($this->symbols, '', $minPrice);
        }

        // set max price
        $maxPrice = $request->input('maxPrice', null);
        if ($maxPrice !== NULL) {
            $maxPrice = (int)str_replace($this->symbols, '', $maxPrice);
        }

        // set selected min amount
        $selectedAmount = $request->input('amount', null);
        if ($selectedAmount !== NULL) {
            $selectedAmount = (int)str_replace($this->symbols, '', $selectedAmount);

        }
        // set selected max amount
        $selectedAmount2 = $request->input('amount2', null);
        if ($selectedAmount2 !== NULL) {
            $selectedAmount2 = (int)str_replace($this->symbols, '', $selectedAmount2);
        }

        // set min price if selectedAmount is null
        if ($minPrice !== NULL && $selectedAmount === null) {
            $search_params['minPrice'] = $minPrice;
            $search_params['amount'] = $minPrice;
        }

        // set min price to selectedAmount is not null
        if (($selectedAmount >= $minPrice) && ($selectedAmount > 0)) {
            $search_params['minPrice'] = $selectedAmount;
        }

        // set max price if selectedAmount2 is null
        if ($maxPrice && $selectedAmount2 === null) {
            $search_params['maxPrice'] = $maxPrice;
        }

        // set max price to selectedAmount2 is not null
        if ($maxPrice > 0 && $selectedAmount2 <= $maxPrice) {
            $search_params['maxPrice'] = $selectedAmount2;
        }

        if(isset($search_params['maxPrice']) && !isset($search_params['minPrice'])) {
            $search_params['minPrice'] = 0;
        }

        $stores = [];
        $brands = [];
        $brandsIds = [];
        $storesIds = [];

        // store and brand id strings
        if($storesIdsString || $brandsIdsString) {

            if ($brandsIdsString !== null) {
                $brandsIds = explode(';',rtrim($brandsIdsString, ';'));
            }

            if ($storesIdsString !== null) {
                $storesIds = explode(';',rtrim($storesIdsString, ';'));
            }

            if(session('stores') && $q === session('q')) {
                $stores = session('stores');
            } else {
                session()->forget('stores');
                $stores = [];
            }

            if(session('brands') && $q === session('q')) {
                $brands = session('brands');
            } else {
                session()->forget('brands');
                $brands = [];
            }

            $attFilter = $this->buildAttFilter($storesIds, $brandsIds);
            $search_params['attFilter'] = rtrim($attFilter, ';');
        } else {
            $search_params['showAttributes' ] = 'true';
            $search_params['attributeId'] = "{$this->brandIdValue};{$this->storeIdValue}";
        }

        $options = new CategoryOptions($this->provider, $request->all());
        $breadcrumbs = [['title' => 'Home', 'url' => '/']];

        // set the page number
        $page = $request->get('page', 1);
        // set the title
        $title = sprintf('Search result for "%s"', $q);
        // get all featured products
        $allProducts = $this->provider->getFeaturedProducts();


        // set the brands and store strings
        $search_params['brands'] = $brandsIdsString;
        $search_params['stores'] = $storesIdsString;

        // get the products
        $search_params['amount'] = $minPrice;
        $search_params['amount2'] = $maxPrice;

        $products = $this->provider->search($search_params, $page);

        if($selectedAmount === NULL) {
            if (isset($products['min_price']) && $products['min_price'] > 0) {
                $minPrice = ceil($products['min_price'] / 100);
                unset($products['min_price']);
            }
        }

        if($selectedAmount2 === NULL) {
            if (isset($products['max_price']) && $products['max_price'] > 0) {
                $maxPrice = ceil($products['max_price'] / 100);
                unset($products['max_price']);
            }
        }

        /** DE Brands Stores start */
        // 'Marke' - DE Brands
        if(isset($products['Marke'])) {
            $brands = $products['Marke'];
            asort($brands);
            unset($products['Marke']);
        }

        // 'Shops' - DE Stores
        if(isset($products['Shops'])) {
            $stores = $products['Shops'];
            asort($stores);
            unset($products['Shops']);
        }
        /** DE Brands Stores end */

        /** FR Brands Stores start */
        // 'Marques' - FR brands
        if(isset($products['Marques'])) {
            $brands = $products['Marques'];
            asort($brands);
            unset($brands['Marques']);
        }

        // 'Marchands' - FR merchants
        if(isset($products['Marchands'])) {
            $stores = $products['Marchands'];
            asort($stores);
            unset($products['Marchands']);
        }
        /** FR Brands Stores end*/

        if(isset($products['Stores'])) {
            $stores = $products['Stores'];
            asort($stores);
            unset($products['Stores']);
        }

        if(isset($products['Brand'])) {
            $brands = $products['Brand'];
            asort($brands);
            unset($products['Brand']);
        }

        if ($products) {
            $products->setPath("/" . $request->path());
            $products->appends($options->getPaginatorParams());
            $pagination = true;

        } else {
            $products = [];
            $pagination = false;
        }

        if ($q == null) {
            return redirect()->route('home');
        } else {

            // set the session variable
            session(['q' => $q]);
            session(['ps_categories' => $categories]);
            session(['stores' => $stores]);
            session(['brands' => $brands]);
            session(['checkedStoresIds' => $storesIds]);
            session(['checkedBrandsIds' => $brandsIds]);

            return view('offers.index')
                ->with('search', true)
                ->with('title', $title)
                ->with('q', $q)
                ->with('featuredProducts', $products)
                ->with('allProducts', $allProducts)
                ->with('brands', $brands)
                ->with('stores', $stores)
                ->with('checkedStoresIds', $storesIds)
                ->with('checkedBrandsIds', $brandsIds)
                ->with('storeIdValue', $this->storeIdValue)
                ->with('brandIdValue', $this->brandIdValue)
                ->with('minPrice', $minPrice)
                ->with('maxPrice', (int)$maxPrice)
                ->with('selectedMinPrice', $selectedAmount)
                ->with('selectedMaxPrice', $selectedAmount2)
//                ->with('recentArticles', $this->recentArticles())
//                ->with('featuredArticles', $this->featuredArticles())
                ->with('section', 'products')
                ->with('singlePost', false)
                ->with('categories', $categories)
                ->with('options', $options)
                ->with('pagination', $pagination)
                ->with('message', $message)
                ->with('breadcrumbs', $breadcrumbs);
        }

    }

    /**
     * @param $id
     * @return mixed
     */
    public function product($id) {
        $q = null;
        $minPrice = $this->minPrice;
        $maxPrice = $this->maxPrice;

        $storeIdValue = '';
        $brandIdValue = '';

        $product = $this->provider->getProduct($id);
        if (!$product) {
            abort(404);
        }

        $title = $product->getTitle();
        $breadcrumbs = [["title" => $title, 'url' => '/']];

        /*$allProducts = $this->provider->getFeaturedProducts();
        $this->featureProducts($allProducts);*/
        $featuredProducts = $this->featureProducts;

        return view('offers.index')
            ->with('singleProduct', true)
            ->with('title', $title)
            ->with('q', $q)
            ->with('featuredProducts', $featuredProducts)
            ->with('minPrice', $minPrice)
            ->with('maxPrice', $maxPrice)
            ->with('storeIdValue', $storeIdValue)
            ->with('brandIdValue', $brandIdValue)
//            ->with('recentArticles', $this->recentArticles())
            ->with('brands', [])
            ->with('stores', [])
            ->with('minPrice', $minPrice)
            ->with('maxPrice', $maxPrice)
            ->with('singlePost', true)
            ->with('section', 'products')
//            ->with('featuredArticles', $this->featuredArticles())
            ->with('product', $product)
            ->with('categories', $this->categories)
            ->with('breadcrumbs', $breadcrumbs)
            ->with('categories', $this->categories);
    }

    /**
     * generates the attFilter parameter
     *
     * @param array $storeIds
     * @param string $storeIdValue
     * @param array $brandIds
     * @param string $brandIdValue
     * @return string
     */
    private function buildAttFilter($storeIds, $brandIds) {

        // initialize attFilter string
        $attFilter = '';

        // brands ids
        if(!empty($brandIds)) {
            foreach ($brandIds as $brand) {
                $attFilter .= "{$this->brandIdValue}:{$brand};";
            }
        }

        // store ids
        if(!empty($storeIds)) {
            foreach ($storeIds as $store) {
                $attFilter .= "{$this->storeIdValue}:{$store};";
            }
        }


        return $attFilter;

    }

    public function getLocale() {
        return $this->locales[$this->locale]['currency_format'];
    }

    public function getLocalePrefix() {
        return $this->locales[$this->locale]['prefix'];
    }
}