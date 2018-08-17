<?php

namespace SdEvents\DataClasses;

class EventDetails {
    public $start_time;
    public $end_time;
    public $venue;
    public $facebook_url;

    function __construct($start_time, $end_time, $venue, $facebook_url) {
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->venue = $venue;
        $this->facebook_url = $facebook_url;
    }
}
