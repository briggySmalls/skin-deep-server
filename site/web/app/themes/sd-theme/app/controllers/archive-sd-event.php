<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;
use SkinDeep\Events\EventsApi;
use Sober\Controller\Module\Tree;

class ArchiveSdEvent extends Controller implements Tree
{
    public function EventsApi()
    {
        return new EventsApi();
    }
}
