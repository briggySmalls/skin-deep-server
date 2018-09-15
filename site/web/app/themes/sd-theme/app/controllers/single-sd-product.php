<?php

namespace App;

use Sober\Controller\Controller;

class SingleSdProduct extends Controller
{
    public static function id()
    {
        return get_the_ID();
    }

    public static function name()
    {
        return get_the_title();
    }

    public static function price($post_id=null)
    {

        return get_field('sd-product-price', $post_id);
    }

    public static function url($post_id=null)
    {
        return get_permalink($post_id);
    }

    public static function description($post_id=null)
    {
        return get_field('sd-product-description', $post_id);
    }
}
