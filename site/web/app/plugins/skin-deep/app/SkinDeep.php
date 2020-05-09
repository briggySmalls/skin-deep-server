<?php

namespace SkinDeep;

use \YeEasyAdminNotices\V1\AdminNotice;

use SkinDeep\Utilities\ResourceManager;
use SkinDeep\Utilities\Helper;
use SkinDeep\Utilities\Loader;
use SkinDeep\Events\EventsModule;
use SkinDeep\Shop\ShopModule;
use SkinDeep\Articles\ArticlesModule;

/**
 * @brief      Entrypoint for the skin deep plugin
 */
class SkinDeep
{
    /**
     * Helper class for managing actions/filters for the whole plugin
     */
    private $loader;

    /**
     * Module for managing shop functionality
     */
    private $shop;

    /**
     * Module for managing event functionality
     */
    private $events;

    /**
     * Current plugin version.
     */
    public const VERSION = '1.0.0';

    /**
     * Options page settings
     */
    private $options_page;

    /**
     * Sources on which to add crossorigin attribute to resource hint
     */
    const CROSSORIGIN_SOURCES = [
        '//fonts.googleapis.com'
    ];

    public function __construct()
    {
        // Create a new loader
        $this->loader = new Loader();

        // Ensure ACF is present
        $this->loader->addAction('plugins_loaded', function () {
            if (!function_exists('get_field')) {
                AdminNotice::create()
                    ->error('ACF Pro not found: Skin Deep plugin will not work. Contact site admin.')
                    ->show();
            }
        });

        // Register public scripts & styles
        $resources = new ResourceManager(__DIR__);
        $this->loader->addAction('wp_enqueue_scripts', function () use ($resources) {
            // Get the google tracking ID
            $id = self::getGoogleTrackingId();
            wp_enqueue_script(
                'google-tag-manager',
                "https://www.googletagmanager.com/gtag/js?id={$id}"
            );
            wp_enqueue_script('skindeep-plugin-public', $resources->distURL() . 'public.js');
        });

        // Add plugin options page
        $this->loader->addAction('acf/init', [$this, 'addOptionsPage']);

        // Add tags to scripts
        $this->loader->addFilter('script_loader_tag', self::staticMethod('updateScripts'), 10, 2);
        // Add preconnect for external assets
        $this->loader->addFilter('wp_resource_hints', self::staticMethod('preconnectExternalAssets'), 10, 2);

        // Register widgets
        $this->loader->addAction('widgets_init', self::staticMethod('registerWidgets'));

        // Do some general setting up
        $this->loader->addAction('wp_print_styles', self::staticMethod('dequeueDashicons'), 100);

        // Remove native taxonomy field from attachments (we use ACF)
        $this->loader->AddFilter('attachment_fields_to_edit', self::staticMethod('removeNativeArtistInPopup'));
        $this->loader->AddFilter('register_taxonomy_args', self::staticMethod('removeNativeArtistInEditor'), 10, 2);
    }

    /**
     * @brief      Run the loader to execute all of the hooks with WordPress.
     * @return     false
     */
    public function run()
    {
        // Execute all actions/filters
        $this->loader->run();
    }

    /**
     * @brief      Register plugin's widgets
     * @return     false
     */
    public static function registerWidgets()
    {
        register_widget('SkinDeep\Widgets\PostsPreview\PostsPreview');
        register_widget('SkinDeep\Widgets\PostsSlider\PostsSlider');
        register_widget('SkinDeep\Widgets\PostSuggestions\PostSuggestions');
        register_widget('SkinDeep\Widgets\Donations\Donation');
    }

    /**
     * @brief      Add attributes to scripts
     * @param      $tag     The script tag HTML
     * @param      $handle  The handle of the script
     * @return     The filtered script tag HTML
     */
    public static function updateScripts($tag, $handle)
    {
        if ($handle == 'google-tag-manager') {
            // Add attributest to google tag manager
            $id = self::getGoogleTrackingId();
            return Helper::updateTag(
                $tag,
                "id=\"google-tag-manager-script\" data-google-tracking-id=\"{$id}\" async"
            );
        }
        return $tag;
    }

    /**
     * @brief      Dequeues the Dashicons CSS from the frontend
     * @return     None
     */
    public static function dequeueDashicons()
    {
        if (!is_user_logged_in()) {
            // Remove icons if user not logged in
            wp_deregister_style('dashicons');
        }
    }

    /**
     * @brief      Add preconnect browser hints
     * @note       Wordpress already adds dns-prefetch for all external links
     * @param      $urls           The urls
     * @param      $relation_type  The relation type
     * @return     urls/attributes for resource hinting
     */
    public static function preconnectExternalAssets($urls, $relation_type)
    {
        if ($relation_type === 'preconnect') {
            // Create preconnect URLs from unique hosts
            $new_urls = array_map(
                function ($url) {
                    // Make URL relative protocol (http/https depending on connection)
                    $entry = ['href' => "//{$url}"];
                    return $entry;
                },
                wp_dependencies_unique_hosts()
            );
            // Add the new preconnect URLs to any existing ones
            $urls = array_merge($urls, $new_urls);
            // Add special preconnect for google fonts
            $urls[] = [
                'href' => '//fonts.gstatic.com',
                'crossorigin',
            ];
        }
        return $urls;
    }

    /**
     * @brief      Removes a native artist from editor.
     * @param      $args      The arguments
     * @param      $taxonomy  The taxonomy
     * @return     $args (with meta_box_cb modified for sd_artist taxonomies)
     */
    public static function removeNativeArtistInEditor($args, $taxonomy)
    {
        if ($taxonomy === 'sd_artist') {
            $args['meta_box_cb'] = false;
        }
        return $args;
    }

    /**
     * @brief      Remove native artist taxonomy field from attachment editor
     * @param      $fields  The fields to show
     * @return     $fields but missing artist taxonomy
     */
    public static function removeNativeArtistInPopup($fields)
    {
        unset($fields['sd_artist']);
        return $fields;
    }

    /**
     * @brief      Helper method to build static method string
     * @param      $name  The name of the function
     * @return     Static method string
     */
    private static function staticMethod($name)
    {
        return __NAMESPACE__ . "\\SkinDeep::$name";
    }

    /**
     * @brief      Helper function to get Google Tracking ID
     * @return     The google tracking identifier.
     */
    private static function getGoogleTrackingId()
    {
        return get_field('sd_general_google_analytics_id', 'option');
    }

    /**
     * @brief      Add an options page to configure the plugin settings
     * @return     false
     */
    public function addOptionsPage()
    {
        // Create event settings
        $this->options_page = acf_add_options_page([
            'page_title' => 'Skin Deep Settings',
            'capability' => 'edit_posts',
        ]);
    }

    /**
     * @brief      Create the submodules of the site
     * @return     false
     */
    public function createModules()
    {
        // Create modules
        $this->articles = new ArticlesModule($this->loader);
        $this->events = new EventsModule($this->loader);
        $this->shop = new ShopModule($this->loader);
    }
}
