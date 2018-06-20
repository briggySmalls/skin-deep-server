<?php

namespace App;

// Remove existing 'authors' base URL
add_filter('author_rewrite_rules', function() {
    return [];
});
