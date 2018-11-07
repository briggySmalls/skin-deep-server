<?php

namespace SkinDeep\Events;

use SkinDeep\Shop\Loader;
use SkinDeep\Events\FacebookApi;
use \YeEasyAdminNotices\V1\AdminNotice;
use \DateTime;

class Plugin
{
    //! Name of the environment variable that holds the google maps API key
    const GOOGLE_MAPS_FIELD_NAME = 'sd_event_google_maps_api_key';

    const EVENT_STATUS_QUERY_ARG = 'status';

    protected static $status_to_comparison_map = [
        'past' => '<',
        'upcoming' => '>=',
    ];

    protected $loader;

    protected $settings_page_info;

    public function __construct() {
        // Create a loader
        $this->loader = new Loader();

        // Immediate setup
        $this->createEventSettings();

        // Add some hooks
        $this->loader->addAction('init', [$this, 'addEventStatusQuery']);
        $this->loader->addAction('pre_get_posts', [$this, 'filterEventsOnStatus']);
        $this->loader->addAction('save_post', [$this, 'updateEventWithFacebookDetails'], 1);
        $this->loader->addAction('acf/init', [$this, 'checkEventSettings']);
    }

    public function run()
    {
        $this->loader->run();
    }

    public function createEventSettings() {
        // Setup event plugin options
        if (function_exists('acf_add_options_page')) {
            $this->settings_page_info = acf_add_options_page([
                'page_title' => 'Event Settings',
                'capability' => 'edit_posts',
                'parent_slug' => 'edit.php?post_type=sd-event',
                'redirect' => false
            ]);
        } else {
            AdminNotice::create()
                ->error('ACF Pro not found: Skin Deep Shop plugin will not work')
                ->show();
        }
    }

    public function addEventStatusQuery() {
        // Register a new rewrite tag (notify wordpress of custom query arg)
        add_rewrite_tag('%' . self::EVENT_STATUS_QUERY_ARG . '%', '([^&]+)');
        // Add a rewrite rule for events
        add_rewrite_rule(
            '^events/' . self::EVENT_STATUS_QUERY_ARG . '/([^/]*)/?',
            'index.php?post_type=sd-event&status=$matches[1]',
            'top');
    }

    public function filterEventsOnStatus($query) {
        if (!is_admin() && // Do not mess up admin lists
                $query->is_main_query() && // Preserve menus etc.
                is_post_type_archive('sd-event') &&
                get_query_var(self::EVENT_STATUS_QUERY_ARG)) {
            // Determine if we are looking for past/upcoming
            $status = get_query_var(self::EVENT_STATUS_QUERY_ARG);
            if (!array_key_exists($status, self::$status_to_comparison_map)) {
                // Return no posts for an invalid query arg
                $query->set('post__in', [0]);
                return;
            }

            //Get original meta query
            $meta_query = is_array($query->get('meta_query')) ? $query->get('meta_query') : [];
            //Add our meta query to the original meta queries
            $meta_query[] = [
                'key' => 'sd_event_details_end_time',
                'compare' => self::$status_to_comparison_map[$status],
                'value' => date(DateTime::ATOM),
            ];
            // Update the query
            $query->set('meta_query', $meta_query);
        }
    }

    public function updateEventWithFacebookDetails($post_id) {
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
    }

    public function checkEventSettings() {
        // Get URL of settings page
        $url = admin_url($this->settings_page_info['parent_slug'] . '&page=' . $this->settings_page_info['menu_slug']);

        // Deal with google maps registration
        $google_maps_key = get_field(self::GOOGLE_MAPS_FIELD_NAME, 'option');
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
        foreach (['app_id', 'app_secret', 'access_token'] as $field) {
            if (!(array_key_exists($field, $facebook_details) && $facebook_details[$field])) {
                // Warn that google API isn't going to work
                AdminNotice::create()
                    ->error()
                    ->html("Event setting '$field' not yet set. Configure in <a href=\"$url\">Events -&gt; Event Settings</a>")
                    ->show();
            }
        }
    }
}
