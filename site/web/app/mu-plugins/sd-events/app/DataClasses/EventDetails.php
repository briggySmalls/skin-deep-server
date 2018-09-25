<?php

namespace SkinDeep\Events\DataClasses;

class EventDetails
{
    public $start_time;
    public $end_time;
    public $venue;
    public $event_id;

    public function __construct($start_time, $end_time, $venue, $event_id)
    {
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->venue = $venue;
        $this->event_id = $event_id;
    }
}
