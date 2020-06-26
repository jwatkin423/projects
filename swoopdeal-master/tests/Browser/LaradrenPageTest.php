<?php

namespace Tests\Browser;


use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Http\Controllers\BlogController;

class LaradrenPageTest extends DuskTestCase
{

    /**
     * @group  home
     */
    public function testHomePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Blog')
                    ->assertSee('Home')
                    ->assertSee('About');
        });
    }


    /**
     * @group about
     */
    public function testAboutUsPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/about')
                    ->assertSee('About Us');
        });
    }


    /**
     * @group about
     * @group privacy
     */
    public function testPrivacyPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/about/privacy')
                    ->assertSee('Privacy Policy');
        });
    }

    /**
     * @group about
     * @group terms
     */
    public function testTermsPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/about/terms')
                    ->assertSee('Content')
                    ->assertSee('Warranty')
                    ->assertSee('License')
                    ->assertSee('Trademarks');
        });
    }

    /**
     * @group blog
     */
    public function testBlogPage()
    {
        $this->browse(function (Browser $browser) {

            $Blog = new BlogController();

            $recentArticles = $Blog->recentArticles();

            $article = $recentArticles[0];
            $post_date = $article['post_date'];
            $title = $article['title'];

            $browser->visit('/blog')
                    ->assertSee($post_date)
                    ->assertSee($title);
        });
    }


}
