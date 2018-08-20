<?php

namespace SdEvents\DataClasses;

use \DateTime;

const FACEBOOK_EVENTS_URL_BASE = 'https://www.facebook.com/events/';

class EventDetails
{
    public $start_time;
    public $end_time;
    public $venue;
    public $event_id;

    function __construct($start_time, $end_time, $venue, $event_id)
    {
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->venue = $venue;
        $this->event_id = $event_id;
    }

    public function facebookUrl()
    {
        return FACEBOOK_EVENTS_URL_BASE . $this->event_id;
    }
}
