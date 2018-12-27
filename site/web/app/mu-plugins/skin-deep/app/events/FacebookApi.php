<?php

namespace SkinDeep\Events;

use SkinDeep\Events\DataClasses\FacebookEventDetails;
use SkinDeep\Events\FacebookApiException;
use \Facebook\Facebook;
use \Facebook\Exceptions\FacebookResponseException;
use \Facebook\Exceptions\FacebookSDKException;
use \YeEasyAdminNotices\V1\AdminNotice;

class FacebookApi
{
    protected $fb;

    const APP_ID_KEY = 'app_id'; //!< Option field name for facebook App ID
    const APP_SECRET_KEY = 'app_secret'; //!< Option field name for facebook App secret
    const ACCESS_TOKEN_KEY = 'page_access_token'; //!< Option field name for facebook page access token

    public function __construct()
    {
        // Get facebook settings
        $fb_settings = get_field(EventsModule::FACEBOOK_OPTIONS_GROUP, 'option');
        // Create facebook API
        $this->fb = new Facebook([
          'app_id' => $fb_settings[self::APP_ID_KEY],
          'app_secret' => $fb_settings[self::APP_SECRET_KEY],
          'default_graph_version' => 'v3.1',
          'default_access_token' => $fb_settings[self::ACCESS_TOKEN_KEY],
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
            throw new FacebookApiException('Facebook graph API returned an error', 0, $e);
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            throw new FacebookApiException('Facebook SDK returned an error', 0, $e);
        }
        return null;
    }
}
