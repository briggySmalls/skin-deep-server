<?php

namespace SkinDeep\Theme;

class ChildOnlyNavWalker extends \Walker_Nav_Menu
{
    function start_lvl(&$output, $depth = 0, $args = array())
    {
        if ($depth == 0) {
            // We start at level 1
            return;
        }
        parent::start_lvl($output, $depth, $args);
    }

    function end_lvl(&$output, $depth = 0, $args = array())
    {
        if ($depth == 0) {
            // We start at level 1
            return;
        }
        parent::end_lvl($output, $depth, $args);
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        if ($depth == 0) {
            // We start at level 1
            return;
        }
        parent::start_el($output, $item, $depth, $args);
    }

    function end_el(&$output, $item, $depth = 0, $args = array())
    {
        if ($depth == 0) {
            // We start at level 1
            return;
        }
        parent::end_el($output, $item, $depth, $args);
    }

    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {
        // Check if element as a 'current element' class
        $current_element_markers = ['current-menu-item', 'current-menu-parent', 'current-menu-ancestor'];
        $current_class = array_intersect($current_element_markers, $element->classes);

        // If element has a 'current' class, it is an ancestor of the current element
        $ancestor_of_current = !empty($current_class);

        // If this is a top-level link and not the current, or ancestor of the current menu item - stop here.
        if (0 == $depth && !$ancestor_of_current) {
            return;
        }

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}
