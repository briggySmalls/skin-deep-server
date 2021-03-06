<?php
// TODO: Decide whether to take these instructions seriously
// facebook/graph-sdk suggests installing paragonie/random_compat (Provides a better CSPRNG option in PHP 5)
// facebook/graph-sdk suggests installing guzzlehttp/guzzle (Allows for implementation of the Guzzle HTTP client)


namespace SkinDeep\Events;

use SkinDeep\Module;
use SkinDeep\Events\FacebookApi;
use \YeEasyAdminNotices\V1\AdminNotice;
use \DateTime;

/**
 * @brief      Entrypoint for the events module
 */
class EventsModule extends Module
{
    //! Name of the ACF field variable that holds the google maps API key
    const GOOGLE_MAPS_FIELD_NAME = 'sd_events_google_maps_api_key';

    //! Name of the ACF field group for facebook settings
    const FACEBOOK_OPTIONS_GROUP = 'sd_events_facebook';

    //! Meta key for storing facebook location
    const FACEBOOK_PLACE_META_KEY = 'sd_event_facebook_place';

    const EVENT_STATUS_QUERY_ARG = 'status';

    const EVENT_POST_TYPE = 'sd-event';

    protected static $status_to_comparison_map = [
        'past' => '<',
        'upcoming' => '>=',
    ];

    protected $loader;

    public function init()
    {
        // Add some hooks
        $this->getLoader()->addFilter('registered_post_type', [$this, 'addEventStatusQuery'], 10, 2);
        $this->getLoader()->addFilter('get_the_archive_title', [$this, 'addStatusToTitle'], 11);
        $this->getLoader()->addAction('pre_get_posts', [$this, 'filterEventsOnStatus']);
        $this->getLoader()->addAction('save_post', [$this, 'updateEventWithFacebookDetails'], 1);
        $this->getLoader()->addAction('acf/init', [$this, 'checkEventSettings'], 20);
    }

    public function addEventStatusQuery($post_type, $args)
    {
        if (($post_type === self::EVENT_POST_TYPE) && $args->has_archive) {
            $slug = get_post_type_archive_link($post_type);
            $slug = str_replace(home_url(), '', $slug);
            $slug = trim($slug, '/');

            // Use a page instead of the archive
            $page = get_page_by_path($slug, OBJECT);
            if (isset($page)) {
                global $wp_rewrite;
                $wp_rewrite->extra_rules_top["{$slug}/?$"] = "index.php?pagename={$slug}";
            }

            // Register a new rewrite tag (notify wordpress of custom query arg)
            add_rewrite_tag('%' . self::EVENT_STATUS_QUERY_ARG . '%', '([^&]+)');
            // Add a rewrite rule for events
            add_rewrite_rule(
                "^{$slug}/" . self::EVENT_STATUS_QUERY_ARG . '/([^/]+)/?$',
                'index.php?post_type=' . $post_type . '&' . self::EVENT_STATUS_QUERY_ARG . '=$matches[1]',
                'top'
            );
            // Add a rewrite rule for paged events
            add_rewrite_rule(
                "^{$slug}/" . self::EVENT_STATUS_QUERY_ARG . '/([^/]+)/page/([0-9]{1,})/?$',
                'index.php?post_type=' . $post_type . '&' .
                self::EVENT_STATUS_QUERY_ARG . '=$matches[1]&paged=$matches[2]',
                'top'
            );
        }
    }

    public function addStatusToTitle($title)
    {
        if (is_post_type_archive(EventsModule::EVENT_POST_TYPE) &&
                get_query_var(self::EVENT_STATUS_QUERY_ARG)) {
            # Add status to archive
            $status = get_query_var(self::EVENT_STATUS_QUERY_ARG);
            switch ($status) {
                case 'upcoming':
                    return "Upcoming Events";

                case 'past':
                    return "Past Events";

                default:
                    assert(false, "Unexpected status");
                    break;
            }
        }
        return $title;
    }

    public function filterEventsOnStatus($query)
    {
        if ($query->is_admin() || // Do not mess up admin lists
            !$query->is_main_query() || // Preserve menus
            !is_post_type_archive(self::EVENT_POST_TYPE) || // Ignore non-event queries
            !get_query_var(self::EVENT_STATUS_QUERY_ARG)) { // Ignore queries that do not specify event status
            // Short-circuit
            return;
        }

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
        $meta_query[] = self::getStatusMetaQuery($status);
        // Update the query
        $query->set('meta_query', $meta_query);
        // Sort by date (reverse-chronological)
        $query->set('orderby', ['meta_value' => 'DESC']);
    }

    public function updateEventWithFacebookDetails($post_id)
    {
        // Only check events
        if (get_post_type($post_id) != self::EVENT_POST_TYPE) {
            return;
        }

        // Check we have a facebook event
        $facebook_event = get_field('sd_event_facebook_event', $post_id);
        if (!$facebook_event) {
            return;
        }

        // Update details with facebook event data
        $api = new FacebookApi();
        try {
            $details = $api->getEventDetails($facebook_event);
        } catch (FacebookApiException $e) {
            AdminNotice::create()
                ->error('Facebook API exception: ' . $e->getMessage())
                ->show();
            // return;
        }

        // Update date and time (also clears Google maps fields)
        update_field('sd_event_details', $details->toAcfDetails(), $post_id);
        // Save facebook location in DB
        self::addOrUpdate($post_id, self::FACEBOOK_PLACE_META_KEY, maybe_serialize($details->venue));
    }

    public function checkEventSettings()
    {
        // Deal with google maps registration
        $google_maps_key = get_field(self::GOOGLE_MAPS_FIELD_NAME, 'option');
        if ($google_maps_key) {
            // Set google API key
            acf_update_setting('google_api_key', $google_maps_key);
        } else {
            // Warn that google API isn't going to work
            AdminNotice::create()
                ->error()
                ->html("Google maps API key not set. Congfigure in Event Settings")
                ->show();
        }

        // Check we have a facebook access token
        $facebook_details = get_field('sd_events_facebook', 'option');
        if (!$facebook_details) {
            // Warn that facebook API isn't going to work
            AdminNotice::create()
                ->error()
                ->html("Event facebook settings unset. Configure in Event Settings")
                ->show();
        } else {
            foreach (['app_id', 'app_secret', 'page_access_token'] as $field) {
                if (!(array_key_exists($field, $facebook_details) && $facebook_details[$field])) {
                    // Warn that facebook API isn't going to work
                    AdminNotice::create()
                        ->error()
                        ->html("Event setting '$field' not yet set. Configure in Event Settings")
                        ->show();
                }
            }
        }
    }

    public static function isValidEventPage()
    {
        return array_key_exists(
            get_query_var(self::EVENT_STATUS_QUERY_ARG),
            self::$status_to_comparison_map
        );
    }

    public static function getStatusMetaQuery($status)
    {
        // Construct a meta query to the original meta queries
        return [
            'key' => 'sd_event_details_start_time',
            'compare' => self::$status_to_comparison_map[$status],
            'value' => date(DateTime::ATOM),
        ];
    }

    public static function getStatusUrl($status)
    {
        return '/events/' . self::EVENT_STATUS_QUERY_ARG . "/$status";
    }

    protected static function addOrUpdate($post_id, $key, $value)
    {
        if (!add_post_meta($post_id, $key, $value, true)) {
            update_post_meta($post_id, $key, $value);
        }
    }
}
