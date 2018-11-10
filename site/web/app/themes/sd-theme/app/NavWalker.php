<?php

namespace SkinDeep\Theme;

use Roots\Soil\Nav\NavWalker as Soil;

/**
 * Return if Soil does not exist.
 */
if (!class_exists('Roots\Soil\Nav\NavWalker')) {
    return;
}

class NavWalker extends Soil
{
    public function __construct()
    {
        // Prevent custom post behaviour of Soil's nav walker
    }

    // Fix https://github.com/roots/soil/issues/212
    public function walk($elements, $max_depth)
    {
        // Add filters
        add_filter('nav_menu_css_class', array($this, 'cssClasses'), 10, 2);
        add_filter('nav_menu_item_id', '__return_null');
        // Perform usual walk
        $output = call_user_func_array(['parent', 'walk'], func_get_args());
        // Unregister filters
        remove_filter('nav_menu_css_class', [$this, 'cssClasses']);
        remove_filter('nav_menu_item_id', '__return_null');
        // Return result
        return $output;
    }

    public function cssClasses($classes, $item)
    {
        /* Remove page hierarchy classes (which Soil's walker marks as 'active')
         * Note: For example, a single post is a child of post archive in page hierarchy
         */
        $classes = preg_replace('/(current([-_]page[-_])(item|parent|ancestor))/', '', $classes);

        // Continue on
        return parent::cssClasses($classes, $item);
    }
}
