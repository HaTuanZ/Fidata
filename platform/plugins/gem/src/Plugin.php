<?php

namespace Botble\Gem;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('gems');
        Schema::dropIfExists('gems_translations');
    }
}
