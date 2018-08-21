<?php

namespace SdEvents;

use SdEvents\DataClasses\EventDetails;
use \DateTime;

class SdEventsApi
{
    public function getEventDetails()
    {
        $details = get_field('sd_event_details');
        return new EventDetails(
            self::toDatetime($details['start_time']),
            self::toDatetime($details['end_time']),
            $details['venue']['address'],
            get_field('sd_event_facebook_event')
        );
    }

    private static function toDatetime($datetime_string)
    {
        return DateTime::createFromFormat(DateTime::ATOM, $datetime_string);
    }
}
