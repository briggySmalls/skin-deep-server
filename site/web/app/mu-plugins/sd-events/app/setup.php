<?php

namespace App;

//! Name of the environment variable that holds the google maps API key
const GOOGLE_MAPS_FIELD_NAME = "sd_event_google_maps_api_key";

// TODO: Decide whether to take these instructions seriously
// facebook/graph-sdk suggests installing paragonie/random_compat (Provides a better CSPRNG option in PHP 5)
// facebook/graph-sdk suggests installing guzzlehttp/guzzle (Allows for implementation of the Guzzle HTTP client)

add_action('acf/init', function() {
    $google_maps_key = get_field(GOOGLE_MAPS_FIELD_NAME, 'option');
    if ($google_maps_key) {
        // Set google API key
        acf_update_setting('google_api_key', $google_maps_key);
    } else {
        // Warn that google API isn't going to work
        display_error("Google maps API not yet set");
    }
});

// Setup event plugin options
if(function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title'    => 'Event Settings',
        'capability'    => 'edit_posts',
        'parent_slug' => 'edit.php?post_type=sd-event',
        'redirect'      => false
    ));
}

// Display an error
function display_error($message) {
    add_action('admin_notices', function() use ($message) {
        ?>
        <div class="error notice">
            <p><?php _e($message, 'sd_events'); ?></p>
        </div>
        <?php
    });
}
