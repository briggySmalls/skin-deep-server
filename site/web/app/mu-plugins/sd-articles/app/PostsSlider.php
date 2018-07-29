<?php

namespace App;

use App\Widget;
use App\WidgetArgs;

/**
 * Posts slider widget
 * Displays selection of posts in a featured slider
 */
class PostsSlider extends Widget {

    /**
     * Unique identifier for the widget.
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * widget file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected const WIDGET_SLUG = 'slider';

    /*--------------------------------------------------*/
    /* Constructor
    /*--------------------------------------------------*/

    /**
     * Specifies the classname and description, instantiates the widget,
     * loads localization files, and includes necessary stylesheets and JavaScript.
     */
    public function __construct() {
        parent::__construct(
            __( 'Posts Slider', self::WIDGET_SLUG ),
            __( 'Preview of posts in a featured slider.', self::WIDGET_SLUG ));

        // load plugin text domain
        add_action( 'init', array( $this, 'widget_textdomain' ) );
    } // end constructor

    /*--------------------------------------------------*/
    /* Protected Functions
    /*--------------------------------------------------*/

    /**
     * @brief      Factory method for creating args for the widget
     * @param      $args  The arguments
     * @return     { description_of_the_return_value }
     */
    protected function create_args( $args ) {
        return new PostsSliderArgs($args);
    }

    protected function widget_slug() {
        return self::WIDGET_SLUG;
    }

} // end class

class PostsSliderArgs extends WidgetArgs {

    public $args = null;
    public $posts = null;

    public function __construct($args) {
        $this->args = $args;
        $this->posts = $this->get_slider_posts($args);
    }

    protected function get_slider_posts( $args ) {
        return $this->get_acf_field( 'sd_widget_slider_articles' );
    }
}

add_action( 'widgets_init', function () {
    register_widget( 'App\PostsSlider' );
});
