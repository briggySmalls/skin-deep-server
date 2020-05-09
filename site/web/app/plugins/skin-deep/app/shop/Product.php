<?php

namespace SkinDeep\Shop;

use SkinDeep\Common\Post;

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

    public function image()
    {
        if (has_term('magazines', 'sd-product-cat', $this->post->ID) && self::isPhotonActive()) {
            $image_id = get_post_thumbnail_id($this->post->ID);
            return new MagazineImage($image_id);
        }
        return parent::image();
    }

    public function cardClasses()
    {
        return parent::cardClasses() . ($this->inStock() ? ' in-stock' : ' out-of-stock');
    }

    public function backgroundColour()
    {
        return get_field('sd_product_magazine_image_background_colour', $this->post->ID);
    }

    protected static function isPhotonActive()
    {
        return class_exists('Jetpack') && \Jetpack::is_module_active('photon');
    }
}
