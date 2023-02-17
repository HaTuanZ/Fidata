<?php

namespace Botble\ApiKeys\Http\Controllers\API;

use Botble\ApiKeys\Models\Orders;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class OrderController extends BaseController
{

    public function index(Request $request)
    {
        $orders = Orders::where('user_id', $request->user_id)->orderBy('time', 'desc')->get();
        return response()->json($orders);
    }

}
