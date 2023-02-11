<?php

namespace Botble\MarcoEvent;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('marco_events');
        Schema::dropIfExists('marco_events_translations');
    }
}
