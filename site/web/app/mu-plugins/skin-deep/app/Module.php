<?php

namespace SkinDeep;

abstract class Module
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the module.
     */
    private $loader;

    public function __construct($loader) {
        // Save the loader
        $this->loader = $loader;

        // Call the initialise function
        $this->init();
    }

    /**
     * @brief      Function in which module performs setup logic
     * @return     false
     */
    abstract protected function init();

    /**
     * @brief      Get the loader
     * @return     The loader.
     */
    protected function getLoader() {
        return $this->loader;
    }
}
