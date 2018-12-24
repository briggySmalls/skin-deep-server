<?php

namespace SkinDeep\Events;

use \DateTime;
use SkinDeep\Common\Post;

const FACEBOOK_EVENTS_URL_BASE = 'https://www.facebook.com/events/';

class Event extends Post
{
    const GOOGLE_MAPS_QUERY_URL_BASE = 'https://www.google.com/maps/search/?api=1&query=';

    private $start_time;
    private $end_time;
    private $google_place;
    private $facebook_place;
    private $facebookId;

    public function __construct($post)
    {
        // Construct the post wrapper as usual
        parent::__construct($post);
        // Fetch the event details
        $details = get_field('sd_event_details', $post->ID);
        $this->start_time = self::toDatetime($details['start_time']);
        $this->end_time = self::toDatetime($details['end_time']);

        $this->facebookId = get_field('sd_event_facebook_event', $post->ID);
        if ($this->facebookId) {
            // We have a facebook event
            $facebook_place_stored = get_post_meta($post->ID, EventsModule::FACEBOOK_PLACE_META_KEY, true);
            $this->facebook_place = maybe_unserialize($facebook_place_stored);
        } else {
            $this->google_place = $details['venue'];
        }
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
        if ($this->facebookId) {
            if ($this->facebook_place) {
                return $this->facebook_place['name'];
            }
        } elseif ($this->google_place) {
            return $this->google_place['address'];
        }
        return false;
    }

    public function venueUrl()
    {
        if ($this->facebookId) {
            $query = urlencode($this->facebook_place['name']);
            // The facebook event has a location
            if ($this->facebook_place && isset($this->facebook_place['location'])) {
                $location = $this->facebook_place['location'];
                $query .= self::toQuery($location['street']);
                $query .= self::toQuery($location['city']);
                $query .= self::toQuery($location['country']);
                $query .= self::toQuery($location['zip']);
            }
            return self:: GOOGLE_MAPS_QUERY_URL_BASE . $query;
        } elseif ($this->google_place) {
            return self:: GOOGLE_MAPS_QUERY_URL_BASE . self::toQuery($this->google_place['address']);
        }
        return false;
    }

    public function facebookUrl()
    {
        if ($this->facebookId) {
            return FACEBOOK_EVENTS_URL_BASE . $this->facebookId;
        }
        return null;
    }

    private static function toDatetime($datetime_string)
    {
        return $datetime_string ? DateTime::createFromFormat(DateTime::ATOM, $datetime_string) : null;
    }

    private static function toQuery($field)
    {
        if (isset($field)) {
            return ',' . urlencode($field);
        }
        return '';
    }
}
