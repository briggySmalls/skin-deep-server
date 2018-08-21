<?php

namespace App;

use Sober\Controller\Controller;
use SdEvents\SdEventsApi;

class ArchiveSdEvent extends Controller
{
    public function sdEventsApi()
    {
        return new SdEventsApi();
    }
}
