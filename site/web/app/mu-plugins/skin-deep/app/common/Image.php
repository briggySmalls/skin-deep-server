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
            add_filter('wp_calculate_image_srcset', '\SkinDeep\Common\Post::extendSrcSet', 10, 5);
        }

        // Get the image source
        $image = wp_get_attachment_image($this->id, $size, false, $attrs);
        // Remove the height/width attributes (part of our responsive efforts)
        $image = Helper::removeHeightWidth($image);

        if ($is_extended) {
            remove_filter('wp_calculate_image_srcset', '\SkinDeep\Common\Post::extendSrcSet', 10);
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
}
