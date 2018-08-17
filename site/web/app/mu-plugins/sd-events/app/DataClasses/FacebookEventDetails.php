<?php

namespace SdEvents\DataClasses;

const FACEBOOK_EVENTS_URL_BASE = 'https://www.facebook.com/events/';

class FacebookEventDetails extends EventDetails {
    function __construct($response) {
        // Create the core class
        parent::__construct(
            $response->getField('start_time'),
            $response->getField('end_time'),
            $response->getField('place')->getField('name'),
            FACEBOOK_EVENTS_URL_BASE . $response->getField('id'));
    }
}
