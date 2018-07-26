<?php

namespace App;

use App\ResourceManager;
use App\Widget;
use Philo\Blade\Blade;

/**
 * Posts preview widget
 * Displays selection of posts in a taxonomy, format, etc
 */
class PostsPreview extends Widget {

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
    protected const WIDGET_SLUG = 'widget-preview';

    /*--------------------------------------------------*/
    /* Constructor
    /*--------------------------------------------------*/

    /**
     * Specifies the classname and description, instantiates the widget,
     * loads localization files, and includes necessary stylesheets and JavaScript.
     */
    public function __construct() {
        parent::__construct(
            __( 'Posts Preview', self::WIDGET_SLUG ),
            __( 'Preview of posts in a configured group.', self::WIDGET_SLUG ));

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
        return new PostsPreviewArgs($args);
    }

    protected function widget_slug() {
        return self::WIDGET_SLUG;
    }

} // end class

class PostsPreviewArgs {

    public $args = null;
    public $posts = null;

    public function __construct($args) {
        $this->args = $args;
        $this->posts = $this->get_preview_posts($args);
    }

    /**
     * @brief      Helper function to get posts matching widget configuration.
     * @param      $args  The widget output arguments.
     * @return     The posts.
     */
    private function get_preview_posts() {
        // Limit query to specified number of posts
        $query_args = [
            'posts_per_page' => (int)$this->get_acf_field( 'sd_widget_preview_count' ),
        ];

        // Determine what type of filtering we're applying
        $filter_group = $this->get_acf_field( 'sd_widget_preview_filter_group' );
        switch ($filter_group['sd_widget_preview_filter_type']) {
            # Filter articles to the specified category
            case "category":
                $category = $filter_group['sd_widget_preview_category'];
                $query_args['cat'] = $category;
                $this->url = get_term_link( $category );
                break;

            # Filter articles to the specified format
            case "format":
                $format = $filter_group['sd_widget_preview_format'];
                $query_args['tax_query'] = [
                    [
                        'taxonomy' => 'post_format',
                        'field' => 'term_id',
                        'terms' => $format->term_id,
                    ]
                ];
                $this->url = get_post_format_link( $format->name );
                break;

            case "all":
                // Set URL as posts archive
                $this->url = get_post_type_archive_link('post');
                break;

            default:
                assert("Unexpected default");
                break;
        }

        // Execute the query
        return get_posts( $query_args );
    }

    /**
     * Helper function to get an ACF field for the given widget
     */
    public function get_acf_field( $field ) {
        return get_field( $field , 'widget_' . $this->args['widget_id'] );
    }
}

add_action( 'widgets_init', function () {
    register_widget( 'App\PostsPreview' );
});
