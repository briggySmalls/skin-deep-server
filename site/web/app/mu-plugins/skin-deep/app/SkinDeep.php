<?php

namespace SkinDeep;

use SkinDeep\Events\Events;
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

        // Create modules
        $this->shop = new Shop($this->loader);
        $this->events = new Events($this->loader);
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
