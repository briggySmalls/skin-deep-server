<?php

namespace SkinDeep;

use SkinDeep\Events\Plugin;
use SkinDeep\Shop\Shop;
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
     * Sub-plugin for managing shop functionality
     */
    private $shop;

    /**
     * Sub-plugin for managing event functionality
     */
    private $events;

    public function __construct() {
        // Create a new loader
        $this->loader = new Loader();

        // Create sub-plugins
        $this->shop = new Shop($this->loader);
        $this->events = new Plugin($this->loader);
    }

    /**
     * @brief      Run the loader to execute all of the hooks with WordPress.
     * @return     false
     */
    public function run() {
        // Execute all actions/filters
        $this->loader->run();
    }
}
