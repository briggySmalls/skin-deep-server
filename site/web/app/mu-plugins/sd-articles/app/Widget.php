<?php

namespace App;

use App\ResourceManager;
use Philo\Blade\Blade;

/**
 * Featured posts slider widget
 *
 */
abstract class Widget extends \WP_Widget {

    protected $blade;

    /*--------------------------------------------------*/
    /* Constructor
    /*--------------------------------------------------*/

    /**
     * Specifies the classname and description, instantiates the widget,
     * loads localization files, and includes necessary stylesheets and JavaScript.
     */
    public function __construct($title, $description) {

        // load plugin text domain
        add_action( 'init', array( $this, 'widget_textdomain' ) );

        parent::__construct(
            $this->widget_slug() ,
            $title,
            [
                'classname'  => $this->widget_slug() . '-class',
                'description' => $description
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
        // Register the widget
        // add_action( 'widgets_init', function () {
        //     register_widget( 'App\\' . get_class() );
        // });
        // // Hooks fired when the Widget is activated and deactivated
        // register_activation_hook( __FILE__, ['App\\' . get_class(), 'activate' ] );
        // register_deactivation_hook( __FILE__, ['App\\' . get_class(), 'deactivate' ] );
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
        $cache = wp_cache_get( $this->widget_slug() , 'widget' );

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
        $context = $this->create_args($args);

        // Generate the widget content from the Blade template
        $widget_string .= $this->blade->view()->make('widget', ['context' => $context])->render();
        $widget_string .= $after_widget;

        $cache[ $args['widget_id'] ] = $widget_string;

        wp_cache_set( $this->widget_slug() , $cache, 'widget' );

        print $widget_string;

    } // end widget

    public function flush_widget_cache()
    {
        wp_cache_delete( $this->widget_slug() , 'widget' );
    }

    /*--------------------------------------------------*/
    /* Public Functions
    /*--------------------------------------------------*/

    /**
     * Loads the Widget's text domain for localization and translation.
     */
    public function widget_textdomain() {

        // TODO be sure to change 'widget-name' to the name of *your* plugin
        load_plugin_textdomain( $this->widget_slug() , false, ResourceManager::lang_dir());

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
        wp_enqueue_style( $this->widget_slug() . '-admin-styles', ResourceManager::dist_url( 'admin.css' ) );
    }

    /**
     * Registers and enqueues admin-specific JavaScript.
     */
    public function register_admin_scripts() {
        wp_enqueue_script( $this->widget_slug() . '-admin-script', ResourceManager::dist_url() . 'admin.js' );
    }

    /**
     * Registers and enqueues widget-specific styles.
     */
    public function register_widget_styles() {
        wp_enqueue_style( $this->widget_slug() . '-widget-styles', ResourceManager::dist_url() . 'widget.css' );
    }

    /**
     * Registers and enqueues widget-specific scripts.
     */
    public function register_widget_scripts() {
        wp_enqueue_script( $this->widget_slug() . '-script', ResourceManager::dist_url() . 'widget.js' );
    }

    /*--------------------------------------------------*/
    /* Private/protected Functions
    /*--------------------------------------------------*/

    /**
     * @brief      Gets the name of the widget
     * @return     Name of the widget
     */
    protected abstract function widget_slug();

    /**
     * @brief      Gets the arguments for the widget template
     * @param      $args  The array of form elements
     * @return     The processed arguments
     */
    protected abstract function create_args( $args );

} // end class
