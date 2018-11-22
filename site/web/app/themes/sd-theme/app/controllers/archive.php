<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;

use SkinDeep\Articles\Post;
use SkinDeep\Articles\Article;
use SkinDeep\Events\Event;
use SkinDeep\Shop\Product;

class Archive extends Controller
{
    public function cardTemplate()
    {
        if ($this->isArticlesPage()) {
            return 'partials.archive.post';
        } elseif (self::postType() === 'sd-event') {
            return 'partials.archive.event';
        } elseif (self::postType() === 'sd-product') {
            return 'partials.archive.product';
        }
        return 'partials.archive.post';
    }

    public function postWrapperFactory()
    {
        if ($this->isArticlesPage()) {
            return function ($post) {
                return new Article($post);
            };
        } elseif (self::postType() === 'sd-event') {
            return function ($post) {
                return new Event($post);
            };
        } elseif (self::postType() === 'sd-product') {
            return function ($post) {
                return new Product($post);
            };
        }
        // Fall back to an article... it probably is
        // Note: this could be e.g. Video format archive
        return function ($post) {
            return new Article($post);
        };
    }

    protected static function postType()
    {
        // Queried object is the post type, or null if post type is 'post'
        $post_type = get_queried_object();
        if ($post_type === null) {
            return 'post';
        }
        return $post_type->name;
    }

    protected function isArticlesPage()
    {
        if (is_category()) {
            // All category pages are for posts
            return true;
        }
        // Queried object is the post type, or null if post type is 'post'
        $post_type = get_queried_object();
        return ($post_type == null);
    }
}
