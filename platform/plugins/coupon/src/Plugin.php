<?php

namespace Botble\Coupon;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('coupons_translations');
    }
}
