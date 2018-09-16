<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;
use SdEvents\SdEventsApi;

class SingleSdEvent extends Controller
{
    protected $api;

    public function __construct()
    {
        // Create an API to serve us
        $this->api = new SdEventsApi();
    }

    public function details()
    {
        return self::getDetails($this->api);
    }

    public static function getDetails($api)
    {
        return $api->getEventDetails();
    }

    public static function getDisplayTime($details)
    {
        // First check that there are times in the first place
        assert($details->start_time);
        if (!$details->end_time)
        {
            // Only return the start time
            return self::toDatetimeString($details->start_time);
        }

        // Determine if we show special formatting for same day (start - end date)
        $is_same_day = $details->start_time->format('d') == $details->end_time->format('d');
        $is_not_24_hrs = $details->start_time->diff($details->end_time)->format('H') < 24;
        if ($is_same_day & $is_not_24_hrs) {
            // Starts/ends on same day
            return self::toTimeString($details->start_time) . ' - ' . self::toTimeString($details->end_time) . ' ' . self::toDateString($details->start_time);
        }

        // Otherwise write out the date twice
        return (
            self::toDatetimeString($details->start_time) . ' - ' . self::toDatetimeString($details->end_time));
    }

    protected static function toDatetimeString($datetime)
    {
        return self::toTimeString($datetime) . ' ' . self::toDateString($datetime);
    }

    protected static function toTimeString($datetime)
    {
        return date_i18n('H:m', $datetime->getTimestamp());
    }

    protected static function toDateString($datetime)
    {
        return date_i18n('d M', $datetime->getTimestamp());
    }
}
