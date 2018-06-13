<?php

namespace App;

// Remove existing 'authors' base URL
add_filter('author_rewrite_rules', function() {
    return [];
});

// Create author taxonomy
add_action( 'init', function () {
    $labels = [
        'name' => 'Authors', 'taxonomy general name', 'sd-articles'
    ];
    $args = array(
        'public'            => true,
        'hierarchical'      => false,
        'labels'            => $labels,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'author' ),
        'description' => 'Author of the article',
    );
    // TODO: Find a way to specify 'select one' and move default position to 'high' (under title)
    register_taxonomy( 'sd-author', array( 'post' ), $args );
});
