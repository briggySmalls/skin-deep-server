<?php

namespace App\SkinDeep;

/**
 * Wrapper for product data
 */
class Product
{
    protected $post;

    public function __construct($post)
    {
        // Record the post
        $this->post = $post;
    }

    public function title()
    {
        return $this->post->post_title;
    }

    public function id()
    {
        return $this->post->ID;
    }

    public function price()
    {
        return get_field('sd-product-price', $this->post->ID);
    }

    public function url()
    {
        return get_permalink($this->post->ID);
    }

    public function description()
    {
        return get_field('sd-product-description', $this->post->ID);
    }

    public function in_stock()
    {
        return get_field('sd-product-in-stop', $this->post->ID);
    }
}
