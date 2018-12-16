<?php

namespace SkinDeep;

use \YeEasyAdminNotices\V1\AdminNotice;

use SkinDeep\Utilities\ResourceManager;
use SkinDeep\Events\EventsModule;
use SkinDeep\Shop\ShopModule;
use SkinDeep\Articles\ArticlesModule;
use SkinDeep\Utilities\Loader;

/**
 * @brief      Entrypoint for the skin deep plugin
 */
class SkinDeep {
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

    public function __construct() {
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

        // Register widgets
        $this->loader->addAction('widgets_init', __NAMESPACE__ . '\\SkinDeep::registerWidgets');

        // Create modules
        $this->articles = new ArticlesModule($this->loader);
        $this->events = new EventsModule($this->loader);
        $this->shop = new ShopModule($this->loader);
    }

    /**
     * @brief      Run the loader to execute all of the hooks with WordPress.
     * @return     false
     */
    public function run() {
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
}
