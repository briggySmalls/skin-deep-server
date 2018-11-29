<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;

use SkinDeep\Articles\Post;
use SkinDeep\Articles\Article;
use SkinDeep\Events\Event;
use SkinDeep\Shop\Product;

class Archive extends Controller
{
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
