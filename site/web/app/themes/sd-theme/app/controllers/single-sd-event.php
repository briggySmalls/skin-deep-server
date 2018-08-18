<?php

namespace App;

use Sober\Controller\Controller;
use SdEvents\SdEventsApi;

class SingleSdEvent extends Controller
{
    protected $api;

    function __construct() {
        // Create an API to serve us
        $this->api = new SdEventsApi();
    }

    public function details() {
        return self::getDetails($this->api);
    }

    public static function getDetails($api) {
        return $api->getEventDetails();
    }

    public static function toDatetimeString($details) {
        // First determine if the event starts and ends on the same date
        $is_same_day = $details->start_time->format('d') == $details->end_time->format('d');
        $is_not_24_hrs = $details->start_time->diff($details->end_time)->format('H') < 24;
        if ($is_same_day & $is_not_24_hrs) {
            // Starts/ends on same day
            return self::toTimeString($details->start_time) . ' - ' . self::toTimeString($details->end_time) . ' ' . self::toDateString($details->start_time);
        }
        // Otherwise wirte out the date twice
        return (
            self::toTimeString($details->start_time) . ' ' . self::toDateString($details->start_time) . ' - ' .
            self::toTimeString($details->end_time) . ' ' . self::toDateString($details->end_time));
    }

    protected static function toTimeString($datetime) {
        return $datetime->format('H:m');
    }

    protected static function toDateString($datetime) {
        return $datetime->format('d M');
    }
}
