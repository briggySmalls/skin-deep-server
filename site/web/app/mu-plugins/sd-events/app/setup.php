<?php

namespace SkinDeep\Events;

//! Name of the environment variable that holds the google maps API key
const GOOGLE_MAPS_FIELD_NAME = 'sd_event_google_maps_api_key';

// TODO: Decide whether to take these instructions seriously
// facebook/graph-sdk suggests installing paragonie/random_compat (Provides a better CSPRNG option in PHP 5)
// facebook/graph-sdk suggests installing guzzlehttp/guzzle (Allows for implementation of the Guzzle HTTP client)

// Setup event plugin options
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Event Settings',
        'capability' => 'edit_posts',
        'parent_slug' => 'edit.php?post_type=sd-event',
        'redirect' => false
    ));
}

require __DIR__ . '/hooks.php';
