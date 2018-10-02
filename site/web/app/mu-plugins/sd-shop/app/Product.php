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
        if (has_term('magazines', 'sd-product-cat', $this->post->ID) &&
                class_exists('Jetpack') &&
                \Jetpack::is_module_active('photon')) {
            // Product is a magazine, and photon is active - let's make it portrait!
            $img_id = get_post_thumbnail_id($this->post->ID);
            // Get the srcset and modify it to be portrait
            $img_srcset = wp_get_attachment_image_srcset($img_id, 'post-thumbnail');
            $transformed_srcset = preg_replace(
                "/((?<path>.+?)(?<width>\d+)x(?<height>\d+)(?<ext>\..+?) (?<width_2>\d+w))/",
                "$2$4x$3$5 $4w",
                $img_srcset);
            return get_the_post_thumbnail(
                $this->post->ID,
                'post-thumbnail',
                ['srcset' => $transformed_srcset]);
        }
        return parent::image($classes, $sizes, $size);
    }
}
