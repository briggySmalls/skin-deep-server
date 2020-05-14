<?php

namespace SkinDeep\Common;

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
     * @brief      Get featured image object
     * @return     Featured image
     */
    public function image()
    {
        $image_id = get_post_thumbnail_id($this->post->ID);
        if (!$image_id) {
            return false;
        }
        return new Image($image_id);
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
}
