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

    public function inStock()
    {
        return get_field('sd_product_in_stock', $this->post->ID);
    }

    public function image($classes=false, $sizes=false, $size="post-thumbnail")
    {
        if (has_term('magazines', 'sd-product-cat', $this->post->ID) && self::is_photon_active()) {
            // Provide a new size that is portrait
            return get_the_post_thumbnail(
                $this->post->ID,
                [103, 240],
                ['srcset' => $transformed_srcset]);
        }
        return parent::image($classes, $sizes, $size);
    }

    protected static function is_photon_active() {
        return class_exists('Jetpack') && \Jetpack::is_module_active('photon');
    }
}
