<?php

namespace SkinDeep\Events;

use SkinDeep\Events\FacebookApi;
use \YeEasyAdminNotices\V1\AdminNotice;

add_action('acf/init', function () use ($page_info) {
    // Get URL of settings page
    $url = admin_url("${page_info['parent_slug']}&page=${page_info['menu_slug']}");

    // Deal with google maps registration
    $google_maps_key = get_field(GOOGLE_MAPS_FIELD_NAME, 'option');
    if ($google_maps_key) {
        // Set google API key
        acf_update_setting('google_api_key', $google_maps_key);
    } else {
        // Warn that google API isn't going to work
        AdminNotice::create()
            ->error()
            ->html("Google maps API key not set. Congfigure in <a href=\"$url\">Events -&gt; Event Settings</a>")
            ->show();
    }

    // Check we have a facebook access token
    $facebook_details = get_field('sd_event_fb_page_group', 'option');
    foreach(['app_id', 'app_secret', 'access_token'] as $field) {
        if (!(array_key_exists($field, $facebook_details) && $facebook_details[$field])) {
            // Warn that google API isn't going to work
            AdminNotice::create()
                ->error()
                ->html("Event setting '$field' not yet set. Configure in <a href=\"$url\">Events -&gt; Event Settings</a>")
                ->show();
        }
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
