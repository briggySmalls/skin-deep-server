<?php

namespace App;

use Sober\Controller\Controller;
use SdEvents\SdEventsApi;
use Sober\Controller\Module\Tree;

class ArchiveSdEvent extends Controller implements Tree
{
    public function sdEventsApi()
    {
        return new SdEventsApi();
    }
}
