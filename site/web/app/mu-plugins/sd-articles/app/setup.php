<?php

namespace App;

use App\Articles;

const PLUGIN_NAMESPACE = 'App';
const TEMPLATE_NAMESPACE = 'articles';

// Get the blade engine from sage (for building widget html)
add_action('skin_deep_init', function($blade) {
    Articles::$blade = $blade;
});

// Register the widgets
add_action('widgets_init', function () {
    register_widget(PLUGIN_NAMESPACE . '\PostsPreview');
    register_widget(PLUGIN_NAMESPACE . '\PostsSlider');
});

// Remove existing 'authors' base URL
add_filter('author_rewrite_rules', function () {
    return [];
});

// Ensure admins always see the excerpt
add_action('admin_init', function () {
    $user = wp_get_current_user();
    $unchecked = get_user_meta($user->ID, 'metaboxhidden_post', true);
    if ($unchecked) {
        $key = array_search('postexcerpt', $unchecked);
        if (false !== $key) {
            array_splice($unchecked, $key, 1);
            update_user_meta($user->ID, 'metaboxhidden_post', $unchecked);
        }
    }
});
