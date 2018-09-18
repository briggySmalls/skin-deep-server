<?php

namespace SkinDeep\Shop;

use SkinDeep\Articles\Post;

/**
 * Wrapper for product data
 */
class Product extends Post
{
    public function price()
    {
        return get_field('sd-product-price', $this->post->ID);
    }

    public function description()
    {
        return get_field('sd-product-description', $this->post->ID);
    }

    public function in_stock()
    {
        return get_field('sd_product_in_stock', $this->post->ID);
    }
}
