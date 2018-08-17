<?php

namespace SdEvents\DataClasses;

function to_datetime($datetime_string) {
    return \DateTime::createFromFormat(\DateTime::ATOM, $datetime_string);
}

class ManualEventDetails extends EventDetails {
    function __construct($field_group) {
        // Create the core class
        parent::__construct(
            to_datetime($field_group['sd_event_start_time']),
            to_datetime($field_group['sd_event_end_time']),
            $field_group['sd_event_venue']['address'],
            Null);
    }
}
