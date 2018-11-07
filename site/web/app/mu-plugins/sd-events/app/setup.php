<?php

namespace SkinDeep\Events;

use SkinDeep\Events\Plugin;

// TODO: Decide whether to take these instructions seriously
// facebook/graph-sdk suggests installing paragonie/random_compat (Provides a better CSPRNG option in PHP 5)
// facebook/graph-sdk suggests installing guzzlehttp/guzzle (Allows for implementation of the Guzzle HTTP client)

function run_sd_events()
{
    $plugin = new Plugin();
    $plugin->run();
}
run_sd_events();
