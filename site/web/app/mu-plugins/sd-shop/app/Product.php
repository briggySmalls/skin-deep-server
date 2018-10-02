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
            // Product is a magazine, and photon is active - let's make it portrait!
            $img_id = get_post_thumbnail_id($this->post->ID);
            // Get the srcset and modify it to be portrait
            $img_srcset = wp_get_attachment_image_srcset($img_id, 'post-thumbnail');
            $transformed_srcset = preg_replace(
                "/(.+?\?resize=)(?<width>\d+)%2C(?<height>\d+) (?<width_2>\d+)w, /",
                "$1$3%2C$2 $3w, ",
                $img_srcset);
            return sprintf(
                '<img src="%s" srcset="%s" sizes="%s" alt="%s">',
                wp_get_attachment_image_url($img_id, 'full'),
                $transformed_srcset,
                $sizes ?? '100vw',
                get_post_meta($img_id, '_wp_attachment_image_alt', true));
        }
        return parent::image($classes, $sizes, $size);
    }

    protected static function is_photon_active() {
        return class_exists('Jetpack') && \Jetpack::is_module_active('photon');
    }
}
