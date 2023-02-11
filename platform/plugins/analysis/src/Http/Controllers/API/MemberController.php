<?php

namespace Botble\Analysis\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Coin\Repositories\Interfaces\CoinInterface;
use Illuminate\Http\Request;
use Botble\Analysis\Models\CurrencySymbols;
use Botble\Analysis\Models\Wallets;

class MemberController extends Controller
{
    protected $coinRepository;
    protected $x_cg_pro_api_key;

    public function __construct(CoinInterface $coinRepository)
    {
        $this->coinRepository = $coinRepository;
        $this->x_cg_pro_api_key = "CG-39XhuQdDhGGesfdaJso5aWM9";
    }

    public function getCurrencySymbols(Request $request, BaseHttpResponse $response) {
        $type = $request->input("type");
        if($type == "deposit") {
            $data = CurrencySymbols::where([['deposit','=',1],['status','=',1]])->get();
        } else {
            $data = CurrencySymbols::where("status", 1)->get();
        }
        return response()->json($data);
    }
    public function getWallets(Request $request, BaseHttpResponse $response) {
        $currency_symbol_id = $request->input("currency_symbol_id");
        if($currency_symbol_id) {
            $data = Wallets::where('currency_symbol_id', $currency_symbol_id)->get();
        } else {
            $data = Wallets::get();
        }
        return response()->json($data);
    }
}
