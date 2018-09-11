<?php

namespace SdEvents\DataClasses;

use \DateTime;

class FacebookEventDetails extends EventDetails
{
    public function __construct($response)
    {
        // Create the core class
        parent::__construct(
            $response->getField('start_time'),
            $response->getField('end_time'),
            $response->getField('place'),
            $response->getField('id')
        );
    }

    public function toAcfDetails()
    {
        return [
            'start_time' => $this->start_time ? self::toString($this->start_time) : "",
            'end_time' => $this->end_time ? self::toString($this->end_time) : "",
            'venue' => self::toGooglePlace($this->venue),
        ];
    }

    private static function toString($datetime)
    {
        return $datetime->format(DateTime::ATOM);
    }

    private static function toGooglePlace($facebook_place)
    {
        # Fill an empty array for results
        $place = [
            'address' => "",
            'lat' => "",
            'lng' => "",
        ];
        if (!$facebook_place)
        {
            # There is no location data at all. Return early.
            return $place;
        }

        # Add address (if it exists)
        $address = $facebook_place->getField('name');
        if ($address)
        {
            $place['address'] = $address;
        }

        # Add location (if it exists)
        $loc = $facebook_place->getField('location');
        if ($loc)
        {
            $place['lat'] = (string)$loc->getField('latitude');
            $place['lng'] = (string)$loc->getField('longitude');
        }
        return $place;
    }
}
