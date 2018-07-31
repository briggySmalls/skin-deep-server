<?php

namespace App;

use Sober\Controller\Controller;

class SinglePost extends Controller
{
    /**
     * @brief      Get a list of authors
     * @return     List of author taxonomy terms
     */
    public static function authors() {
        return wp_get_post_terms(get_post()->ID, 'sd-author');
    }

    /**
     * @brief      Get featured video
     * @return     Featured video embed
     */
    public static function video() {
        return get_field( 'sd_featured_video', get_post()->ID );
    }

    /**
     * @brief      Get featured image
     * @return     Featured image
     */
    public static function image() {
        return get_the_post_thumbnail(get_post()->ID, 'large', ['class' => 'img-fluid']);
    }

    /**
     * @brief      Determines post has featured video.
     * @return     True if has featured video, False otherwise.
     */
    public static function has_featured_video() {
        return (SinglePost::video() != Null);
    }

    /**
     * @brief      Determines post has featured image.
     * @return     True if has featured image, False otherwise.
     */
    public static function has_featured_image() {
        return has_post_thumbnail(get_post()->ID);
    }
}