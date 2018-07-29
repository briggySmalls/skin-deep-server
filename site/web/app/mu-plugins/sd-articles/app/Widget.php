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
                'classname'  => $this->widget_slug(),
                'description' => $description
            ]
        );

        // Create Laravel Blade handler
        $this->blade = new Blade(ResourceManager::view_dir(), ResourceManager::cache_dir());

        // Refreshing the widget's cached output with each new post
        add_action( 'save_post',    [ $this, 'flush_widget_cache' ] );
        add_action( 'deleted_post', [ $this, 'flush_widget_cache' ] );
        add_action( 'switch_theme', [ $this, 'flush_widget_cache' ] );
        // Register assets (but do not enqueue them)
        add_action('wp_enqueue_scripts', [ $this, 'register_widget_assets' ] );
        add_action('admin_enqueue_scripts', [ $this, 'register_admin_assets' ] );
        // Hooks fired when the Widget is activated and deactivated
        register_activation_hook( __FILE__, ['App\\' . get_class(), 'activate' ] );
        register_deactivation_hook( __FILE__, ['App\\' . get_class(), 'deactivate' ] );
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

        // Enqueue widget styles and scripts now we are going to use them
        $this->enqueue_asset('widget', $is_script=true);
        $this->enqueue_asset('widget', $is_script=false);

        // go on with your widget logic, put everything into a string and …
        extract( $args, EXTR_SKIP );
        $widget_string = $before_widget;

        // Get the context for the template
        $context = $this->create_args($args);

        // Generate the widget content from the Blade template
        $widget_string .= $this->blade->view()->make($this->widget_slug() . '-widget', ['context' => $context])->render();
        $widget_string .= $after_widget;

        $cache[ $args['widget_id'] ] = $widget_string;

        wp_cache_set( $this->widget_slug() , $cache, 'widget' );

        print $widget_string;

    } // end widget

    public function flush_widget_cache()
    {
        wp_cache_delete( $this->widget_slug() , 'widget' );
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

        // Enqueue admin styles and scripts now we are going to use them
        $this->enqueue_asset('admin', $is_script=true);
        $this->enqueue_asset('admin', $is_script=false);

        // TODO: Define default values for your variables
        $instance = wp_parse_args(
            (array) $instance
        );

        // TODO: Store the values of the widget in their own variable

        // Display the admin form
        echo $this->blade->view()->make($this->widget_slug() . '-admin')->render();

    } // end form

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
     * @brief      Enqueues an asset for display
     * @param      $end        Indicates front or admin end ('widget' or 'admin')
     * @param      $is_script  Indicates if script or style
     */
    protected function enqueue_asset( $end, $is_script ) {
        if ($is_script) {
            wp_enqueue_script(
                $this->widget_slug() . '-' . $end . '-script',
                ResourceManager::dist_url() . $this->widget_slug() . '/' . $end . '.js' );
        } else {
            wp_enqueue_style(
                $this->widget_slug() . '-' . $end . '-style',
                ResourceManager::dist_url() . $this->widget_slug() . '/' . $end . '.css' );
        }
    }

    /**
     * @brief      Pre-registers the widget assets
     */
    public function register_widget_assets() {
        $this->register_asset( 'widget', $is_script=true );
        $this->register_asset( 'widget', $is_script=false );
    }

    /**
     * @brief      Pre-registers the admin assets
     */
    public function register_admin_assets() {
        $this->register_asset( 'admin', $is_script=true );
        $this->register_asset( 'admin', $is_script=false );
    }

    /**
     * @brief      Helper function to register an asset
     * @param      $end        Indicates front or admin end ('widget' or 'admin')
     * @param      $is_script  Indicates if script or style
     */
    protected function register_asset( $end, $is_script ) {
        if ($is_script) {
            wp_register_script(
                $this->widget_slug() . '-' . $end . '-script',
                ResourceManager::dist_url() . $this->widget_slug() . '/' . $end . '.js' );
        } else {
            wp_register_style(
                $this->widget_slug() . '-' . $end . '-style',
                ResourceManager::dist_url() . $this->widget_slug() . '/' . $end . '.css' );
        }
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
