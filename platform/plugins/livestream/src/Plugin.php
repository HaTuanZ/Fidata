<?php

namespace Botble\Livestream;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('livestreams');
        Schema::dropIfExists('livestreams_translations');
    }
}
