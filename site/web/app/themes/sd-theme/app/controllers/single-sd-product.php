<?php

namespace App;

use Sober\Controller\Controller;

class SingleSdProduct extends Controller
{
    public static function id() {
        return get_the_ID();
    }

    public static function name() {
        return get_the_title();
    }

    public static function price() {
        return get_field('sd-product-price');
    }

    public static function url() {
        return get_permalink();
    }

    public static function description() {
        return get_field('sd-product-description');
    }
}
