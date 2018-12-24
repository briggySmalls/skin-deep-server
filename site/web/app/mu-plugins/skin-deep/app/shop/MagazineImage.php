<?php

namespace SkinDeep\Shop;

use SkinDeep\Utilities\Helper;
use SkinDeep\Common\Image;
use SkinDeep\Theme\ImageManager;

/**
 * @brief      Wrapper class for a magazine image
 */
class MagazineImage extends Image
{
    public function html($options)
    {
        // Get the image details
        $url = self::getImageSrc($this->id);
        if (!$url) {
            return false;
        }
        // Produce a srcset from portrait sizes
        $srcset = self::toSrcSet($url, self::getPortraitSizes());

        // Provide a new size that is portrait
        $sizes = $options['sizes'] ?? "100vw";
        $image_alt = get_post_meta($this->id, '_wp_attachment_image_alt', true);
        return "<img src=\"${url}\" srcset=\"${srcset}\" sizes=\"${sizes}\" alt=\"${image_alt}\">";
    }

    /**
     * @brief      Get URL of the image (accounting for jetpack)
     * @param      $image_id  The image identifier
     * @return     The image source.
     */
    private static function getImageSrc($image_id)
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
    private static function getPortraitSizes()
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

    /**
     * @brief      Convert a set of sizes to a jetpack srcset
     * @param      $src    The source
     * @param      $sizes  The sizes
     * @return     srcset string for use in image HTML
     */
    private static function toSrcSet($src, $sizes)
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
