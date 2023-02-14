<?php

namespace Botble\ApiKeys\Http\Controllers;

use Binance\API;
use Botble\ApiKeys\Models\Orders;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\ApiKeys\Forms\ApiKeysForm;

class OrderController extends BaseController
{

    public function index(Request $request)
    {
        $orders = Orders::orderBy('time', 'desc')->get();
        return response()->json($orders);
    }

}
