<?php

namespace SkinDeep\Events\DataClasses;

use \DateTime;

class FacebookEventDetails extends EventDetails
{
    public function __construct($response)
    {
        // Create the core class
        parent::__construct(
            $response->getField('start_time'),
            $response->getField('end_time'),
            $response->getField('place')->asArray(), // TODO: Handle events with no place
            $response->getField('id')
        );
    }

    public function toAcfDetails()
    {
        return [
            'start_time' => $this->start_time ? self::toString($this->start_time) : "",
            'end_time' => $this->end_time ? self::toString($this->end_time) : "",
            'venue' => ['address' => '', 'lat' => '', 'lng' => ''],
        ];
    }

    private static function toString($datetime)
    {
        return $datetime->format(DateTime::ATOM);
    }
}
