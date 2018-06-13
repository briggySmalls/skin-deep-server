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

        // Register widget fields in admin
        self::register_fields();
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
     * @brief      Register widget fields using ACF
     * @return     None
     */
    private static function register_fields() {
        // TODO: Move this outside of the class...
        if( function_exists('acf_add_local_field_group') ) {
            acf_add_local_field_group( [
                'key' => 'group_5b14788dad192',
                'title' => 'Preview options',
                'fields' => [
                    [
                        'key' => 'field_5b14795f2eb5b',
                        'label' => 'Title',
                        'name' => 'sd_widget_preview_title',
                        'type' => 'text',
                        'value' => NULL,
                        'instructions' => 'Title to display for the preview section',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5b1478a42eb59',
                        'label' => 'Category',
                        'name' => 'sd_widget_preview_category',
                        'type' => 'taxonomy',
                        'value' => NULL,
                        'instructions' => 'Filter posts by a given category',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'taxonomy' => 'category',
                        'field_type' => 'select',
                        'allow_null' => 1,
                        'add_term' => 0,
                        'save_terms' => 0,
                        'load_terms' => 0,
                        'return_format' => 'id',
                        'multiple' => 0,
                    ],
                    [
                        'key' => 'field_5b1478f22eb5a',
                        'label' => 'Format',
                        'name' => 'sd_widget_preview_format',
                        'type' => 'taxonomy',
                        'value' => NULL,
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'taxonomy' => 'post_format',
                        'field_type' => 'select',
                        'allow_null' => 1,
                        'add_term' => 0,
                        'save_terms' => 0,
                        'load_terms' => 0,
                        'return_format' => 'id',
                        'multiple' => 0,
                    ],
                ],
                'location' => [
                    [
                        [
                            'param' => 'widget',
                            'operator' => '==',
                            'value' => 'widget-name',
                        ],
                    ],
                ],
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ]);
        }
    }

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
    public const POSTS_PER_ROW = 3; // TODO: Make this a plugin option? A widget option?

    public function __construct($args) {
        $this->args = $args;
        $this->posts = self::get_preview_posts($args);
    }

    /**
     * @brief      Helper function to get posts matching widget configuration.
     * @param      $args  The widget output arguments.
     * @return     The posts.
     */
    private static function get_preview_posts($args) {
        // Get widget values
        $category = get_field( 'sd_widget_preview_category', 'widget_' . $args['widget_id'] );
        $format = get_field( 'sd_widget_preview_format', 'widget_' . $args['widget_id'] );

        // Query for posts filtered by widget parameters
        $query_args = [
            'posts_per_page' => POSTS_PER_PAGE // TODO: Make this a plugin option
        ];

        // Filter posts to a particular category (if specified)
        if ($category) {
          $query_args['cat'] = $category;
        }
        // Filter posts to a particular format (if specified)
        if ($format) {
          $query_args['tax_query'] = [
            [
              'taxonomy' => 'post_format',
              'field' => 'term_id',
              'terms' => $format,
            ]
          ];
        }
        return get_posts( $query_args );
    }
}

add_action( 'widgets_init', function () {
    register_widget( 'App\PostsPreview' );
});

// Hooks fired when the Widget is activated and deactivated
register_activation_hook( __FILE__, ['App\PostsPreview', 'activate' ] );
register_deactivation_hook( __FILE__, ['App\PostsPreview', 'deactivate' ] );
