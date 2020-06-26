<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adrenalads\Corcel\WordPressPostFormatter;
use App\Models\AdrenPost as Post;


class BlogController extends Controller {

    private $WordPressPostFormatter;
    private $section = 'blog';

    public function __construct() {
        parent::__construct();
        $this->WordPressPostFormatter = new WordPressPostFormatter;
    }

    public function index() {

        $featuredProducts = $this->featureProducts;
        $Post = new Post();

        if ($this->locale != 'en') {

            $Posts = $Post->getTranslated($this->locale)->with(['attachment', 'revision', 'author'])->paginate(5);
        } else {
            $Posts = $Post->published()->orderBy('ID', 'desc')->with(['attachment', 'revision', 'author'])->paginate(5);
        }


        $recentArticles = $this->recentArticles();

        return view('blog.index')
            ->with('blogs', $Posts)
            ->with('featuredProducts', $featuredProducts)
            ->with('title', 'Swoopdeal: Recent Posts')
            ->with('section', 'blog')
            ->with('recentArticles', $recentArticles)
            ->with('categories', $this->categories);
    }

    public function showSinglePost(Request $request, $slug) {

        $featuredProducts = $this->featureProducts;

        $Posts = new Post();

        $Post = $Posts->type('post')->slug($slug)->translate($this->locale)->first();

        $WordPressPostFormatter = $this->WordPressPostFormatter;

        $article = [
            'post_id' => $Post->ID,
            'post_date' => $Post->post_date->format('m/d/Y'),
            'content' => $WordPressPostFormatter->wpautop($Post->post_content),
            'title' => $Post->post_title,
            'status' => $Post->post_status,
            'name' => $Post->post_name,
            'slug' => $Post->slug,
            'post_type' => $Post->post_type,
            'display_name' => $Post->author->display_name,
            'nicename' => $Post->author->user_nicename,
            'email' => $Post->author->user_email,
            'meta_data' => $Post->meta->all(),
            'attachment' => $Post->attachment->all(),
            'revision' => $Post->revision->all()
        ];

        $articles = $Post->published()
                         ->take(5)
                         ->orderBy('ID', 'desc')
                         ->with([
                                 'attachment',
                                 'revision',
                                 'author'
                             ])->get();

        $recentArticles = $this->recentArticles();

        return view('blog.singlepost')
            ->with('article', $article)
            ->with('title', 'Swoopdeal: '. $article['title'])
            ->with('recentArticles', $recentArticles)
            ->with('section', $this->section)
            ->with('featuredProducts', $featuredProducts)
            ->with('categories', $this->categories);

    }

}