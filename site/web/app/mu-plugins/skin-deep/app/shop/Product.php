<?php

namespace SkinDeep\Shop;

use SkinDeep\Articles\Post;
use SkinDeep\Theme\ImageManager;

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

    public function image($classes = null, $sizes = null, $size = "post-thumbnail")
    {
        if (has_term('magazines', 'sd-product-cat', $this->post->ID) && self::isPhotonActive()) {
            // Get the image details
            $image_id = get_post_thumbnail_id($this->post->ID);
            $url = self::getImageSrc($image_id);
            if (!$url) {
                return false;
            }
            // Produce a srcset from portrait sizes
            $srcset = self::toSrcSet($url, self::getPortraitSizes());

            // Provide a new size that is portrait
            $sizes = $sizes ?? "100vw";
            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            return "<img src=\"${url}\" srcset=\"${srcset}\" sizes=\"${sizes}\" alt=\"${image_alt}\">";
        }
        return parent::image($classes, $sizes, $size);
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

    protected static function getImageSrc($image_id)
    {
        // Get the image (note this is filtered by jetpack)
        $image_src = wp_get_attachment_image_src($image_id, 'full');
        if (!$image_src) {
            // There is no associated attachment
            return false;
        }
        list($url, $width, $height, $is_intermediate) = $image_src;
        assert(!$is_intermediate);
        // Drop any query string
        $url_parts = parse_url($url);
        return sprintf('%s://%s%s', $url_parts['scheme'], $url_parts['host'], $url_parts['path']);
    }

    /**
     * @brief      Get sizes based on landscape dimensions
     * @return     The portrait sizes.
     */
    protected static function getPortraitSizes()
    {
        $heights = ImageManager::WIDTHS;
        $sizes = [];
        foreach ($heights as $height) {
            // Add new width and height to sizes array
            $sizes[] = [
                round($height / 1.42),
                $height
            ];
        }
        return $sizes;
    }

    protected static function toSrcSet($src, $sizes)
    {
        $srcset = '';
        foreach ($sizes as $i => $size) {
            // Add the image
            list($width, $height) = $size;
            $srcset .= "${src}?resize=${width},${height} ${width}w, ";
        }
        return rtrim($srcset, ', ');
    }
}
