<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;

class Archive extends Controller
{
    public function isArticlesPage()
    {
        if (is_category()) {
            // All category pages are for posts
            return true;
        }
        // Queried object is the post type, or null if post type is 'post'
        $post_type = get_queried_object();
        return ($post_type == null);
    }

    public function singlePostTemplate()
    {
        if ($this->isArticlesPage()) {
            return 'articles::partials.single-post';
        } else if (self::postType() === 'sd-product') {
            return 'partials.shop.archive-product';
        }
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
}
