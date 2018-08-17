<?php

namespace SdEvents;

use SdEvents\DataClasses\FacebookEventDetails;
use SdEvents\DataClasses\ManualEventDetails;

const FACEBOOK_EVENTS_URL_BASE = 'facebook.com/events/';

class SdEventsApi {
    protected $fb;

    function __construct() {
        // Get facebook settings
        $fb_settings = get_field('sd_event_fb_page_group', 'option');
        // Create facebook API
        $this->fb = new \Facebook\Facebook([
          'app_id' => $fb_settings['app_id'],
          'app_secret' => $fb_settings['app_secret'],
          'default_graph_version' => 'v3.1',
          'default_access_token' => $fb_settings['access_token'],
        ]);
    }

    public function getEventDetails() {
        // Determine whether we are using facebook or manual results
        $fb_event_id = $this->get_facebook_event();
        if ($fb_event_id) {
            // Make request to facebook
            $response = $this->request($fb_event_id);
            // Create facebook event details
            return new FacebookEventDetails($response->getGraphNode());
        } else {
            // Create details from manual fields
            return new ManualEventDetails(get_field('sd_event_details'));
        }
    }

    public function get_facebook_url() {

        return FACEBOOK_EVENTS_URL_BASE . $this->get_facebook_event();
    }

    private function get_facebook_event() {
        return get_field('sd_event_facebook_event');
    }

    private function request($slug) {
        try {
            // Returns a `Facebook\FacebookResponse` object
            return $this->fb->get('/' . $slug);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
        }
    }
}
