<?php

namespace App;

// Include the PostsPreview widget
require_once __DIR__ . '/PostsPreview.php';
require_once __DIR__ . '/PostsSlider.php';

// Remove existing 'authors' base URL
add_filter('author_rewrite_rules', function () {
    return [];
});

// Ensure admins always see the excerpt
add_action('admin_init', function () {
    $user = wp_get_current_user();
    $unchecked = get_user_meta($user->ID, 'metaboxhidden_post', true);
    $key = array_search('postexcerpt', $unchecked);
    if (false !== $key) {
        array_splice($unchecked, $key, 1);
        update_user_meta($user->ID, 'metaboxhidden_post', $unchecked);
    }
});
