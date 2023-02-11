<?php

namespace Botble\Coin;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('coins');
        Schema::dropIfExists('coins_translations');
    }
}
