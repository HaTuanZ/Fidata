<?php

namespace Botble\ApiKeys;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('api_keys');
        Schema::dropIfExists('api_keys_translations');
    }
}
