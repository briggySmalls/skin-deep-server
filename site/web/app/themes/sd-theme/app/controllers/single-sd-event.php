<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;
use SkinDeep\Events\EventsApi;
use SkinDeep\Events\Event;

class SingleSdEvent extends Controller
{
    public function event()
    {
        return new Event(get_post());
    }

    public static function getDisplayTime($event)
    {
        // First check that there are times in the first place
        assert($event->start_time());
        if (!$event->end_time())
        {
            // Only return the start time
            return self::toDatetimeString($event->start_time());
        }

        // Determine if we show special formatting for same day (start - end date)
        $is_same_day = $event->start_time()->format('d') == $event->end_time()->format('d');
        $is_not_24_hrs = $event->start_time()->diff($event->end_time())->format('H') < 24;
        if ($is_same_day & $is_not_24_hrs) {
            // Starts/ends on same day
            return self::toTimeString($event->start_time()) . ' - ' . self::toTimeString($event->end_time()) . ' ' . self::toDateString($event->start_time());
        }

        // Otherwise write out the date twice
        return (
            self::toDatetimeString($event->start_time()) . ' - ' . self::toDatetimeString($event->end_time()));
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
