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
            'start_time' => self::toString($this->start_time),
            'end_time' => self::toString($this->end_time),
            'venue' => self::toGooglePlace($this->venue),
        ];
    }

    private static function toString($datetime)
    {
        return $datetime->format(DateTime::ATOM);
    }

    private static function toGooglePlace($facebook_place)
    {
        return [
            'address' => $facebook_place->getField('name'),
            'lat' => (string)$facebook_place->getField('location')->getField('latitude'),
            'lng' => (string)$facebook_place->getField('location')->getField('longitude'),
        ];
    }
}
