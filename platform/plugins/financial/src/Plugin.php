<?php

namespace Botble\Financial;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('financials');
        Schema::dropIfExists('financials_translations');
    }
}
