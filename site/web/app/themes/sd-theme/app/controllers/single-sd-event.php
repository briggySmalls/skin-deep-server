<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;
use SkinDeep\Events\EventsApi;
use SkinDeep\Events\Event;

class SingleSdEvent extends Controller implements SingleControllerInterface
{
    public function post()
    {
        return new Event(get_post());
    }

    public static function getDisplayTime($event)
    {
        // First check that there are times in the first place
        assert($event->startTime());
        if (!$event->endTime()) {
            // Only return the start time
            return self::toDatetimeString($event->startTime());
        }

        // Determine if we show special formatting for same day (start - end date)
        $is_same_day = $event->startTime()->format('d') == $event->endTime()->format('d');
        $is_not_24_hrs = $event->startTime()->diff($event->endTime())->format('H') < 24;
        if ($is_same_day & $is_not_24_hrs) {
            // Starts/ends on same day
            return self::toTimeString($event->startTime()) . ' - ' . self::toTimeString($event->endTime()) . ' ' . self::toDateString($event->startTime());
        }

        // Otherwise write out the date twice
        return (
            self::toDatetimeString($event->startTime()) . ' - ' . self::toDatetimeString($event->endTime()));
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
