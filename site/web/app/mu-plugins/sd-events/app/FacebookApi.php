<?php

namespace SdEvents;

use SdEvents\DataClasses\FacebookEventDetails;
use \Facebook\Facebook;
use \Facebook\Exceptions\FacebookResponseException;
use \Facebook\Exceptions\FacebookSDKException;

class FacebookApi
{
    protected $fb;

    public function __construct()
    {
        // Get facebook settings
        $fb_settings = get_field('sd_event_fb_page_group', 'option');
        // Create facebook API
        $this->fb = new Facebook([
          'app_id' => $fb_settings['app_id'],
          'app_secret' => $fb_settings['app_secret'],
          'default_graph_version' => 'v3.1',
          'default_access_token' => $fb_settings['access_token'],
        ]);
    }

    public function getEventDetails($event_id)
    {
        // Make request to facebook
        $response = $this->request($event_id);
        // Obtain event data
        $event_data = $response->getGraphNode();
        // Construct event details object
        return new FacebookEventDetails($event_data);
    }

    private function request($slug)
    {
        try {
            // Returns a `Facebook\FacebookResponse` object
            return $this->fb->get('/' . $slug);
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
        }
    }
}
