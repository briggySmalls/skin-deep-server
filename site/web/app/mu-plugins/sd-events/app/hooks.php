<?php

namespace SkinDeep\Events;

use SkinDeep\Events\FacebookApi;

add_action('acf/init', function () {
    // Deal with google maps registration
    $google_maps_key = get_field(GOOGLE_MAPS_FIELD_NAME, 'option');
    if ($google_maps_key) {
        // Set google API key
        acf_update_setting('google_api_key', $google_maps_key);
    } else {
        // Warn that google API isn't going to work
        display_error('Google maps API not yet set');
    }

    // Check we have a facebook access token
    $facebook_token = get_field('sd_event_facebook_token', 'option');
    if (!$facebook_token) {
        display_error('Facebook page access token not yet set');
    }
});

// Update saved post with facebook event info
add_action('save_post', function ($post_id) {
    // Only check events
    if (get_post_type($post_id) != 'sd-event') {
        return;
    }

    // Check we have a facebook event
    $facebook_event = get_field('sd_event_facebook_event', $post_id);
    if (!$facebook_event) {
        return;
    }

    // Update details with facebook event data
    $api = new FacebookApi();
    $details = $api->getEventDetails($facebook_event)->toAcfDetails();
    update_field('sd_event_details', $details, $post_id);
}, 1);

// Display an error
function display_error($message)
{
    add_action('admin_notices', function () use ($message) {
        ?>
        <div class="error notice">
            <p><?php _e($message, 'sd_events'); ?></p>
        </div>
        <?php
    });
}
