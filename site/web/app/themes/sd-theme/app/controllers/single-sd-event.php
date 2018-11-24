<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;
use SkinDeep\Events\EventsApi;
use SkinDeep\Events\Event;

class SingleSdEvent extends Controller implements SingleControllerInterface
{
    public const DAY_FORMAT = 'jS';
    public const MONTH_FORMAT = 'M';
    public const DAY_AND_MONTH_FORMAT = 'jS M';
    public const TIME_FORMAT = 'H:i';

    public function post()
    {
        return new Event(get_post());
    }

    public static function getDisplayTime($event)
    {
        assert($event->startTime());
        return self::toTimeString($event->startTime());
    }

    public static function getDisplayDate($event)
    {
        // First check that there are times in the first place
        assert($event->startTime());
        if (!$event->endTime()) {
            // Only return the start time
            return self::toDateString($event->startTime());
        }

        // Determine if we show special formatting for same day (start - end date)
        $is_same_day = $event->startTime()->format('d') == $event->endTime()->format('d');
        return $is_same_day ?
            self::toDateString($event->startTime()) :
            self::toDateRange($event->startTime(), $event->endTime());
    }

    protected static function toTimeString($datetime)
    {
        return date_i18n(self::TIME_FORMAT, $datetime->getTimestamp());
    }

    protected static function toDateString($datetime)
    {
        return date_i18n(self::DAY_AND_MONTH_FORMAT, $datetime->getTimestamp());
    }

    protected static function toDateRange($start, $end)
    {
        $is_same_month = $start->format('n') == $end->format('n');

        if ($is_same_month) {
            return sprintf(
                '%s - %s',
                date_i18n(self::DAY_FORMAT, $start->getTimestamp()),
                date_i18n(self::DAY_AND_MONTH_FORMAT, $end->getTimestamp())
            );
        }

        return sprintf(
            '%s - %s',
            date_i18n(self::DAY_AND_MONTH_FORMAT, $start->getTimestamp()),
            date_i18n(self::DAY_AND_MONTH_FORMAT, $end->getTimestamp())
        );
    }
}
