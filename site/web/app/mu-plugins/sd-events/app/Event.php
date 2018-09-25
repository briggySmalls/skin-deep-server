<?php

namespace SkinDeep\Events;

use \DateTime;
use SkinDeep\Articles\Post;

class Event extends Post
{
    protected $start_time;
    protected $end_time;
    protected $venue;
    protected $facebookId;

    public function __construct($post)
    {
        // Construct the post wrapper as usual
        parent::__construct($post);
        // Fetch the event details
        $details = get_field('sd_event_details');
        $this->start_time = self::toDatetime($details['start_time']);
        $this->end_time = self::toDatetime($details['end_time']);
        $this->venue = $details['venue'] ? $details['venue']['address'] : null;
        $this->facebookId = get_field('sd_event_facebook_event');
    }

    public function startTime()
    {
        return $this->start_time;
    }

    public function endTime()
    {
        return $this->end_time;
    }

    public function venue()
    {
        return $this->venue;
    }

    public function facebookUrl()
    {
        if ($this->event_id) {
            return FACEBOOK_EVENTS_URL_BASE . $this->event_id;
        }
        return null;
    }

    private static function toDatetime($datetime_string)
    {
        return $datetime_string ? DateTime::createFromFormat(DateTime::ATOM, $datetime_string) : null;
    }
}
