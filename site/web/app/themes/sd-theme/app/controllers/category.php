<?php

namespace App;

use Sober\Controller\Controller;

class Category extends Controller
{
    public static function id()
    {
        return get_queried_object();
    }

    public static function image($size)
    {
        $image = get_field('sd_article_category_image', Category::id());
        return wp_get_attachment_image($image, $size, false, ['class' => 'img-fluid']);
    }

    public static function description()
    {
        return category_description(Category::id());
    }
}
