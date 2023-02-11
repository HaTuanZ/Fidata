<?php

namespace Botble\Package;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('packages');
        Schema::dropIfExists('packages_translations');
    }
}
