<?php

namespace SkinDeep\Common;

use SkinDeep\Utilities\Helper;

/**
 * @brief      Wrapper class for an image
 */
class Image
{
    protected $id;

    public function __construct($id)
    {
        // Record id
        $this->id = $id;
    }

    /**
     * @brief      Get the HTML corresponding to an image
     * @param      $options  The options for displaying the image
     * @return     Image HTML
     */
    public function html($options)
    {
        $attrs = [];
        self::copyIfSet($attrs, 'class', $options, 'classes');
        self::copyIfSet($attrs, 'sizes', $options, 'sizes');
        $size = $options['size'] ?? 'post-thumbnail';

        // Check if we want an extended srcset
        $is_extended = isset($options['extended']) && $options['extended'];
        if ($is_extended) {
            add_filter('wp_calculate_image_srcset', '\SkinDeep\Common\Image::extendSrcSet', 10, 5);
        }

        // Get the image source
        $image = wp_get_attachment_image($this->id, $size, false, $attrs);
        // Remove the height/width attributes (part of our responsive efforts)
        $image = Helper::removeHeightWidth($image);

        if ($is_extended) {
            remove_filter('wp_calculate_image_srcset', '\SkinDeep\Common\Image::extendSrcSet', 10);
        }

        return $image;
    }

    /**
     * @brief      Get the artists associated with the image
     * @return     Array of artists if any, otherwise false
     */
    public function artists()
    {
        return wp_get_post_terms($this->id, 'sd_artist');
    }

    /**
     * @brief      Helper function to merge arrays
     * @param      $dest        The destination array
     * @param      $dest_key    The destination key
     * @param      $source      The source array
     * @param      $source_key  The source key
     * @return     Merged array
     */
    private static function copyIfSet(&$dest, $dest_key, $source, $source_key)
    {
        if (isset($source[$source_key])) {
            $dest[$dest_key] = $source[$source_key];
        }
    }

    /**
     * @brief      Get extended srcset for an image, including original image
     *
     *             Wordpress automatically creates a srcset for an image, and
     *             correctly only includes sizes with the same aspect ratio.
     *             However we have a need to show the original image, even if it
     *             has a different aspect ratio to ASPECT_RATIO, on large
     *             devices
     *
     * @return     srcset that includes original image size
     */
    public static function extendSrcSet($sources, $size_array, $image_src, $image_meta, $attachment_id)
    {
            list($url, $width, $height, $is_intermediate) = wp_get_attachment_image_src($attachment_id, 'full');
            // Add original image
            $sources[$width] = [
                'url' => $url,
                'value' => $width,
                'descriptor' => 'w'
            ];

        return $sources;
    }
}
