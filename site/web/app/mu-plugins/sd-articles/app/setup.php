<?php

namespace SkinDeep\Articles;

use \YeEasyAdminNotices\V1\AdminNotice;

const TEMPLATE_NAMESPACE = 'articles';

add_action('plugins_loaded', function () {
    if (!function_exists('get_field')) {
        AdminNotice::create()
            ->error('ACF Pro not found: Skin Deep Articles plugin will not work')
            ->show();
    }
});

// Get the blade engine from sage (for building widget html)
add_action('skin_deep_init', function ($blade) {
    Article::$blade = $blade;
});

// Register the widgets
add_action('widgets_init', function () {
    register_widget(__NAMESPACE__ . '\PostsPreview');
    register_widget(__NAMESPACE__ . '\PostsSlider');
    register_widget(__NAMESPACE__ . '\PostSuggestions');
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
