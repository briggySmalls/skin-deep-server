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
     * @return     Featured image
     */
    public function image($size = "full")
    {
        return get_the_post_thumbnail($this->post->ID, $size);
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
