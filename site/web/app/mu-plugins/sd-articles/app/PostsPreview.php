<?php

namespace App;

use App\ResourceManager;
use Philo\Blade\Blade;

// TODO: make these plugin options
const POSTS_PER_PAGE = 6;

/**
 * WordPress Widget Boilerplate
 *
 */
class PostsPreview extends \WP_Widget {

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
    protected const WIDGET_SLUG = 'widget-name';
    protected $blade;

    /*--------------------------------------------------*/
    /* Constructor
    /*--------------------------------------------------*/

    /**
     * Specifies the classname and description, instantiates the widget,
     * loads localization files, and includes necessary stylesheets and JavaScript.
     */
    public function __construct() {

        // load plugin text domain
        add_action( 'init', array( $this, 'widget_textdomain' ) );

        // TODO: update description
        parent::__construct(
            self::WIDGET_SLUG ,
            __( 'Posts Preview', self::WIDGET_SLUG ),
            [
                'classname'  => self::WIDGET_SLUG . '-class',
                'description' => __( 'Preview of posts in a configured group.', self::WIDGET_SLUG )
            ]
        );

        // Create Laravel Blade handler
        $this->blade = new Blade(ResourceManager::view_dir(), ResourceManager::cache_dir());

        // Register admin styles and scripts
        add_action( 'admin_print_styles', [ $this, 'register_admin_styles' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_scripts' ] );
        // Register site styles and scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'register_widget_styles' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_widget_scripts' ] );
        // Refreshing the widget's cached output with each new post
        add_action( 'save_post',    [ $this, 'flush_widget_cache' ] );
        add_action( 'deleted_post', [ $this, 'flush_widget_cache' ] );
        add_action( 'switch_theme', [ $this, 'flush_widget_cache' ] );
    } // end constructor

    /*--------------------------------------------------*/
    /* Widget API Functions
    /*--------------------------------------------------*/

    /**
     * Outputs the content of the widget.
     *
     * @param array args  The array of form elements
     * @param array instance The current instance of the widget
     */
    public function widget( $args, $instance ) {
        // Check if there is a cached output
        $cache = wp_cache_get( self::WIDGET_SLUG , 'widget' );

        if ( !is_array( $cache ) )
            $cache = array();

        if ( ! isset ( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset ( $cache[ $args['widget_id'] ] ) )
            return print $cache[ $args['widget_id'] ];

        // go on with your widget logic, put everything into a string and …
        extract( $args, EXTR_SKIP );
        $widget_string = $before_widget;

        // Get the context for the template
        $context = new WidgetArgs($args);

        // Generate the widget content from the Blade template
        $widget_string .= $this->blade->view()->make('widget', ['context' => $context])->render();
        $widget_string .= $after_widget;

        $cache[ $args['widget_id'] ] = $widget_string;

        wp_cache_set( self::WIDGET_SLUG , $cache, 'widget' );

        print $widget_string;

    } // end widget

    public function flush_widget_cache()
    {
        wp_cache_delete( self::WIDGET_SLUG , 'widget' );
    }

    /**
     * Processes the widget's options to be saved.
     *
     * @param array new_instance The new instance of values to be generated via the update.
     * @param array old_instance The previous instance of values before the update.
     */
    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        // TODO: Here is where you update your widget's old values with the new, incoming values

        return $instance;

    } // end update

    /**
     * Generates the administration form for the widget.
     *
     * @param array instance The array of keys and values for the widget.
     */
    public function form( $instance ) {

        // TODO: Define default values for your variables
        $instance = wp_parse_args(
            (array) $instance
        );

        // TODO: Store the values of the widget in their own variable

        // Display the admin form
        echo $this->blade->view()->make('admin')->render();

    } // end form

    /*--------------------------------------------------*/
    /* Public Functions
    /*--------------------------------------------------*/

    /**
     * Loads the Widget's text domain for localization and translation.
     */
    public function widget_textdomain() {

        // TODO be sure to change 'widget-name' to the name of *your* plugin
        load_plugin_textdomain( self::WIDGET_SLUG , false, ResourceManager::lang_dir());

    } // end widget_textdomain

    /**
     * Fired when the plugin is activated.
     *
     * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
     */
    public static function activate( $network_wide ) {
        // TODO define activation functionality here
    } // end activate

    /**
     * Fired when the plugin is deactivated.
     *
     * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
     */
    public static function deactivate( $network_wide ) {
        // TODO define deactivation functionality here
    } // end deactivate

    /**
     * Registers and enqueues admin-specific styles.
     */
    public function register_admin_styles() {
        wp_enqueue_style( self::WIDGET_SLUG . '-admin-styles', ResourceManager::asset_url( 'styles/admin.css' ) );
    }

    /**
     * Registers and enqueues admin-specific JavaScript.
     */
    public function register_admin_scripts() {
        wp_enqueue_script( self::WIDGET_SLUG . '-admin-script', ResourceManager::asset_url( 'scripts/admin.js' ) );
    }

    /**
     * Registers and enqueues widget-specific styles.
     */
    public function register_widget_styles() {
        wp_enqueue_style( self::WIDGET_SLUG . '-widget-styles', ResourceManager::asset_url( 'styles/widget.css' ) );
    }

    /**
     * Registers and enqueues widget-specific scripts.
     */
    public function register_widget_scripts() {
        wp_enqueue_script( self::WIDGET_SLUG . '-script', ResourceManager::asset_url( 'scripts/widget.js' ) );
    }

} // end class

class WidgetArgs {

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

// Hooks fired when the Widget is activated and deactivated
register_activation_hook( __FILE__, ['App\PostsPreview', 'activate' ] );
register_deactivation_hook( __FILE__, ['App\PostsPreview', 'deactivate' ] );
