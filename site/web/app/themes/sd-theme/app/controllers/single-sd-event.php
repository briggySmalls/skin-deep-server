<?php

namespace App;

use Sober\Controller\Controller;

class SingleSdEvent extends Controller
{
    public static function start_time() {
        $details = self::details();
        return $details['sd_event_start_time'];
    }

    public static function end_time() {
        $details = self::details();
        return $details['sd_event_end_time'];
    }

    public static function date() {
        $details = self::details();
        return $details['sd_event_date'];
    }

    public static function venue() {
        $details = self::details();
        return $details['sd_event_venue']['address'];
    }

    public static function facebook_url() {
        return get_field('sd_event_facebook_event');
    }

    private static function details() {
        return get_field('sd_event_details');
    }
}
