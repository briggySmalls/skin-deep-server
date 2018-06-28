<?php

namespace App;

// Include the PostsPreview widget
require_once __DIR__ . '/PostsPreview.php';

// Remove existing 'authors' base URL
add_filter('author_rewrite_rules', function() {
    return [];
});

