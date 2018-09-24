<?php

namespace SkinDeep\Articles;

use SkinDeep\Shop\Product;

class Article extends Post
{
    public static $blade;

    /**
     * @brief      Get the authors attached to the post
     * @return     Array of WP_Term objects
     */
    public function authors()
    {
        return wp_get_post_terms($this->post->ID, 'sd-author');
        ;
    }

    /**
     * @brief      Get the categories this article is in
     * @note       This does NOT include the default category, as we don't care
     *             about it
     * @return     Array of WP_Term objects
     */
    public function categories()
    {
        $all_categories = get_the_category($this->post->ID);
        $default_index = self::getDefaultIndex($all_categories);
        array_splice($all_categories, $default_index, 1);
        return $all_categories;
    }

    /**
     * @brief      Determines post has featured video.
     * @return     True if has featured video, False otherwise.
     */
    public function hasFeaturedVideo()
    {
        return (self::video() != null);
    }

    /**
     * @brief      Get the magazine attached to the article
     * @return     A Product object if attached, otherwise null
     */
    public function magazine()
    {
        $magazine_post = get_field('sd_article_magazine');
        if ($magazine_post) {
            return new Product($magazine_post);
        }
        return null;
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
     * @brief      Route other attribute accesses to wrapped post object
     * @param      $name  The name of the attribute
     * @return     Value of $name attribute on wrapped post object
     */
    public function __get($name)
    {
        return $this->post->$name;
    }

    protected static function isDefaultCategory($category)
    {
        return self::defaultCategory() == $category->term_id;
    }

    protected static function defaultCategory()
    {
        return get_option('default_category');
    }

    protected static function getDefaultIndex($categories)
    {
        foreach ($categories as $i => $category) {
            if (self::isDefaultCategory($category)) {
                return $i;
            }
        }
        assert(false);
    }
}
