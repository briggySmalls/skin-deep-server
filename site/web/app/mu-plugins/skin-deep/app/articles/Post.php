<?php

namespace SkinDeep\Articles;

/**
 * @brief      Wrapper class for a post (of any post type)
 */
class Post
{
    protected $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * @brief      Get the permalink of the article
     * @return     Permalink
     */
    public function url()
    {
        return get_permalink($this->post);
    }

    /**
     * @brief      Determines post has featured image.
     * @return     True if has featured image, False otherwise.
     */
    public function hasImage()
    {
        return has_post_thumbnail($this->post->ID);
    }

    /**
     * @brief      Get featured image
     * @param      $classes  Classes to add to the image element
     * @param      $sizes    Responsive 'sizes' attribute for image element
     * @param      $size     The nominal wordpress size (default to small)
     * @return     Featured image
     */
    public function image($options)
    {
        $attrs = [];
        self::copyIfSet($attrs, 'class', $options, 'classes');
        self::copyIfSet($attrs, 'sizes', $options, 'sizes');
        $size = $options['size'] ?? 'post-thumbnail';

        // Check if we want an extended srcset
        if (isset($options['extended']) && $options['extended']) {
            add_filter('wp_calculate_image_srcset', '\SkinDeep\Articles\Post::extendSrcSet', 10, 5);
        }

        $image = get_the_post_thumbnail($this->post->ID, $size, $attrs);

        if (isset($options['extended']) && $options['extended']) {
            remove_filter('wp_calculate_image_srcset', '\SkinDeep\Articles\Post::extendSrcSet', 10);
        }

        return $image;
    }

    /**
     * @brief      Determines post has featured video.
     * @return     True if has featured video, False otherwise.
     */
    public function hasVideo()
    {
        return (self::video() != null);
    }

    /**
     * @brief      Get the article's featured video
     * @return     HTML or false if no video attached
     */
    public function video()
    {
        return get_field('sd_featured_video', $this->post->ID);
    }

    /**
     * @brief      Return an unescaped title
     * @note       As this is unescaped, it should be run through {{ }} tags
     * @return     The post title
     */
    public function title()
    {
        return get_the_title($this->post->ID);
    }

    /**
     * @brief      Allow non-articles to be handled by generic template
     * @return     False
     */
    public function categories()
    {
        return false;
    }

    /**
     * @brief      Route other attribute accesses to wrapped post object
     * @param      $name  The name of the attribute
     * @return     Value of $name attribute on wrapped post object
     */
    public function __get($name)
    {
        if (property_exists($this->post, $name)) {
            return $this->post->$name;
        }
        trigger_error(
            sprintf("Undefined property '%s' on %s", $name, get_class($this)),
            E_USER_NOTICE
        );
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

    private static function copyIfSet($dest, $dest_key, $source, $source_key) {
        if (isset($source[$source_key])) {
            $dest[$dest_key] = $source[$source_key];
        }
    }
}
