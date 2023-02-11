<?php

namespace Botble\Coin\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Coin\Repositories\Interfaces\CoinInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Google_Client;
use Google_Service_Sheets;
use Botble\Coin\Models\Coin;
use Botble\Coin\Models\CoinDaily;
use Botble\Coin\Models\CoinCategories;
use Botble\Coin\Models\CoinContract;
use Botble\Coin\Models\CoinDatahub;
use Botble\Coin\Models\CoincapCandles;
use Botble\Coin\Models\CoindarEvents;
use Botble\Coin\Models\CoingeckoTrending;
use Botble\Coin\Models\Cryptorank;
use RvMedia;
use Botble\Coin\Models\Coinglass;
use Botble\Coin\Models\MessariMetrics;
use Botble\Coin\Models\DbtUser as DbtUser;
use Botble\Coin\Models\Member as Member;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Botble\Coin\Models\LlamaRaises;
use Botble\Coin\Models\QuantifycryptoCoins;

class CoinController extends Controller
{
    protected $coinRepository;
    protected $client;
    protected $x_cg_pro_api_key;

    public function __construct(CoinInterface $coinRepository)
    {
        $this->coinRepository = $coinRepository;

        /*$client = new Google_Client();
        $client->setAuthConfig(storage_path('client_secret_700946505764.json'));
        $client->addScope([
            \Google_Service_Sheets::SPREADSHEETS
        ]);

        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);
        $this->client = $client;*/
        $this->x_cg_pro_api_key = "CG-39XhuQdDhGGesfdaJso5aWM9";
    }

    public function getAll(Request $request, BaseHttpResponse $response) {
        $coins = $this->coinRepository
            ->advancedGet([
                'condition' => ['status' => BaseStatusEnum::PUBLISHED],
                'order_by'  => ['coingecko_rank' => 'asc'],
            ]);

        $coin_arr = [];
        foreach ($coins as $coin) {
            $image = $coin->image;
            if($image) {
                $image = str_replace("large", "small", $image);
            }
            $coin_arr[] = array(
                "id"      => $coin->id,
                "coin_id" => $coin->coin_id,
                "name"    => $coin->name,
                "symbol"  => $coin->symbol,
                "image"   => $image
            );
        }
        return response()->json($coin_arr);
    }

    public function getCoin(Request $request, BaseHttpResponse $response)
    {
        $data = array();
        $coin_id = $request->input("coin_id");
        $type = $request->input("type");

        $cache_key = $coin_id."_tracking_".$type;
        $data = Cache::get($cache_key);
        if(empty($data)) {
            $data = array();

            if($type == "_1y") {
                for ($i = 1; $i <= 12; $i++) {
                    $last_date_in_month =  date("Y-m-t", strtotime( date( 'Y-m-01' )." -$i months"));
                    $month_name =  date("M d", strtotime( date( 'Y-m-01' )." -$i months"));

                    $row = CoinDaily::where([['coin_id', '=', $coin_id],['date', '=', $last_date_in_month]])->first();
                    if($row){
                        $category = CoinCategories::where([['coin_id', '=', 'smart-contract-platform'],['date', '=', $row->date]])->first();
                    }
                    $smart_contract_platform = isset($category->market_cap) ? $category->market_cap : 0;
                    $market_cap = isset($row->market_cap) ? $row->market_cap : 0;
                    $dom_pt = 0;
                    if($smart_contract_platform > 0) {
                        $dom_pt = $market_cap / $smart_contract_platform * 100;
                    }
                    $total_supply = isset($row->total_supply) ? $row->total_supply : 0;
                    $price = isset($row->price) ? $row->price : 0;
                    $fully_diluted_valuation = isset($row->fully_diluted_valuation) ? $row->fully_diluted_valuation : 0;
                    if($fully_diluted_valuation <= 0) {
                        $fully_diluted_valuation = $total_supply * $price;
                    }
                    $data[] = array(
                        'date'               => $row->date,
                        'in_month'           => $last_date_in_month,
                        //'label'              => $month_name,
                        'label'              => date("M d Y", strtotime( date( 'Y-m-01' )." -$i months")),
                        'fdv'                => $fully_diluted_valuation,
                        'market_cap'         => $market_cap,
                        'total_volume'       => isset($row->volume) ? $row->volume : 0,
                        'market_cap_scp'     => $smart_contract_platform,
                        'dom_pt'             => $dom_pt ? round($dom_pt, 2) : 0,
                        'total_supply'       => $total_supply,
                        'circulating_supply' => isset($row->circulating_supply) ? $row->circulating_supply : 0,
                        'stake'              => isset($row->stake) ? $row->stake : 0,
                    );
                }
                $data = array_reverse($data);
            } else {
                switch ($type) {
                    case "1y":
                        $max_date = strtotime("-1 year");
                        break;
                    case "90d":
                        $max_date = strtotime("-3 month");
                        break;
                    case "180d":
                        $max_date = strtotime("-6 month");
                        break;
                    case "30d":
                        $max_date = strtotime("-1 month");
                        break;
                    case "7d":
                        $max_date = strtotime("-1 week");
                        break;
                    default:
                        $max_date = strtotime("-10 year");
                        break;
                }
                if($type == "all") {
                    $coins = CoinDaily::where('coin_id', $coin_id)->orderBy('date', 'ASC')->get();
                } else {
                    $date_from = date("Y-m-d", $max_date);
                    $date_to = date("Y-m-d");
                    $coins = CoinDaily::where('coin_id', $coin_id)->whereBetween('date', [$date_from, $date_to])->orderBy('date', 'ASC')->get();
                }
                foreach ($coins as $row) {
                    $last_date_in_month =  date("Y-m-t", strtotime( $row->date ));
                    $month_name =  date("M d", strtotime( $row->date ));
                    //if(strtotime($row->date) >= $max_date) {
                        $category = CoinCategories::where([['coin_id', '=', 'smart-contract-platform'],['date', '=', $row->date]])->first();
                        $market_cap = isset($row->market_cap) ? $row->market_cap : 0;
                        $smart_contract_platform = isset($category->market_cap) ? $category->market_cap : 0;
                        $dom_pt = 0;
                        if($smart_contract_platform > 0) {
                            $dom_pt = $market_cap / $smart_contract_platform * 100;
                        }
                        $total_supply = isset($row->total_supply) ? $row->total_supply : 0;
                        $price = isset($row->price) ? $row->price : 0;
                        $fully_diluted_valuation = isset($row->fully_diluted_valuation) ? $row->fully_diluted_valuation : 0;
                        if($fully_diluted_valuation <= 0) {
                            $fully_diluted_valuation = $total_supply * $price;
                        }
                        if(!is_null($row->date)){
                            $data[] = array(
                                'date'               => $row->date,
                                'in_month'           => $last_date_in_month,
                                'label'              => $month_name,
                                'fdv'                => $fully_diluted_valuation,
                                'market_cap'         => $market_cap,
                                'total_volume'       => isset($row->volume) ? $row->volume : 0,
                                'market_cap_scp'     => $smart_contract_platform,
                                'dom_pt'             => $dom_pt ? round($dom_pt, 2) : 0,
                                'total_supply'       => $total_supply,
                                'circulating_supply' => isset($row->circulating_supply) ? $row->circulating_supply : 0,
                                'stake'              => isset($row->stake) ? $row->stake : 0,
                            );
                        }

                        if($row->coin_id == "near") {
                            $inflation = isset($row->inflation) ? $row->inflation : 0;
                            if($inflation) {
                                $inflation_pt = $row->inflation_pt;
                                $token_release_pt = $inflation_pt - 5;
                                $block_rewards = 4.5;
                                $burn = 0.35;
                                $rebated = 0.15;
                                $sum = $token_release_pt + $block_rewards + $burn + $rebated;
                                $token_release = $inflation * ($token_release_pt/$sum);
                                if($coin_id == "aptos") {
                                    $token_release = isset($row->circulating_supply) ? $row->circulating_supply : 0;
                                }

                                $inflation_data[$month_name] = array(
                                    'token_release'    => (float) $token_release,
                                    'token_release_pt' => (float) $token_release_pt,
                                    'block_rewards_pt' => (float) $block_rewards,
                                    'burn_pt'          => (float) $burn,
                                    'rebated_pt'       => (float) $rebated,
                                );
                            }
                        }
                    //}
                }
            }
            Cache::put($cache_key, $data, 60);
        }
        return response()->json($data);
    }

    public function getMonthlyCoin(Request $request, BaseHttpResponse $response) {
        $data = array();
        $coin_id = $request->input("coin_id");
        $type = $request->input("type") ? $request->input("type") : "1y";

        $cache_key = $coin_id."_monthly_".$type;
        $data = Cache::get($cache_key);
        if(empty($data)) {
            $data = array();

            /*$request_url = "https://coingen.net/api/coin_monthly/$coin_id?type=$type";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $request_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($response);*/
            $today = date("Y-m-d");
            if($type == "30d") {
                $date = date("Y-m-d", strtotime($today." -1 month"));
            } else if($type == "90d") {
                $date = date("Y-m-d", strtotime($today." -3 months"));
            } else if($type == "180d") {
                $date = date("Y-m-d", strtotime($today." -6 months"));
            } else if($type == "1y") {
                $date = date("Y-m-d", strtotime($today." -1 year"));
            } else {
                $date = date("Y-m-d", strtotime($today." -2 year"));
            }
            $request_uri = "https://pro-api.coingecko.com/api/v3/coins/$coin_id?localization=false&tickers=false&community_data=false&developer_data=false&sparkline=false&x_cg_pro_api_key=CG-39XhuQdDhGGesfdaJso5aWM9";
            $request_uri = "https://api.coingecko.com/api/v3/coins/$coin_id?localization=false&tickers=false&community_data=false&developer_data=false&sparkline=false";
            $coin = $this->curl_get($request_uri);
            $market_caps_today = isset($coin->market_data->market_cap->usd) ? $coin->market_data->market_cap->usd : 0;
            $price_today = isset($coin->market_data->current_price->usd) ? $coin->market_data->current_price->usd : 0;
            $coin_circulating_supply = isset($coin->market_data->circulating_supply) ? $coin->market_data->circulating_supply : 0;

            $start    = (new \DateTime($date))->modify('first day of this month');
            $end      = (new \DateTime($today))->modify('first day of next month');
            $interval = \DateInterval::createFromDateString('1 month');
            $period   = new \DatePeriod($start, $interval, $end);
            $data     = array();
            foreach ($period as $dt) {
                $last_date_in_month = $dt->format("Y-m-t");
                $conds = array("date" => $last_date_in_month, "coin_id" => $coin_id);
                $row = CoinDaily::where([['coin_id','=',$coin_id],['date','=',$last_date_in_month]])->first();
                $circulating_supply = isset($row->circulating_supply) ? $row->circulating_supply : 0;

                $prev = (new \DateTime($last_date_in_month))->modify('last day of previous month');
                $prev_date = $prev->format("Y-m-t");
                //$prev_row = $this->Mongod_Coins_model->get_tracking(array("date" => $prev_date, "coin_id" => $coin_id));
                $prev_row = CoinDaily::where([['coin_id','=',$coin_id],['date','=',$prev_date]])->first();
                $prev_circulating_supply = isset($prev_row->circulating_supply) ? $prev_row->circulating_supply : 0;

                $amount_release = abs($circulating_supply - $prev_circulating_supply);
                //if($amount_release <= 0) $amount_release = $circulating_supply;

                $market_cap = isset($row->market_cap) ? $row->market_cap : 0;
                $price = isset($row->price) ? $row->price : 0;

                if(date("Y-m") === $dt->format("Y-m")) {
                    $market_cap = $market_caps_today;
                    $amount_release = $coin_circulating_supply - $prev_circulating_supply;
                    $price = $price_today;
                }

                $start_date_in_month = $dt->format("Y-m-01");
                //$high_price_coin = $this->Mongod_Coins_model->get_high_price_in_range($start_date_in_month, $last_date_in_month, array("coin_id" => $coin_id));

                $test_arr = CoinDaily::where('coin_id', $coin_id)->whereBetween('date', [$start_date_in_month, $last_date_in_month])->orderBy('price', 'desc')->get();
                $high_price_coin = isset($test_arr[0]) ? $test_arr[0] : null;

                $high_price_coin_price = isset($high_price_coin->price) ? round($high_price_coin->price, 2) : 0;

                $data[] = array(
                    'label'                   => $dt->format("M-y"),
                    'market_cap'              => $market_cap,
                    'marlet_price'            => $price,
                    'circulating_supply'      => $circulating_supply,
                    'prev_circulating_supply' => $prev_circulating_supply,
                    'price'                   => $high_price_coin_price,
                    'amount_release'          => round($amount_release, 1),
                );
            }

            Cache::put($cache_key, $data, 60);
        }
        return response()->json($data);
    }

    public function singleCoin(string $coin_id, Request $request, BaseHttpResponse $response)
    {

        if (!$coin_id) {
            return $response->setError()->setCode(404)->setMessage('Not found');
        }
        $data = array();

        $type = $request->get("type") ? $request->get("type") : "1d";

        $cache_key = $coin_id."_single_".$type;
        $data = Cache::get($cache_key);
        if(empty($data)) {
            $data = array();

            $request_url = "https://coingen.net/api/single_coin/$coin_id?type=$type";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $request_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response_data = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($response_data);

            Cache::put($cache_key, $data, 60);
        }
        return response()->json($data);
        //return $response->setData($data)->toApiResponse();
    }

    public function _getCoinPrices(Request $request, BaseHttpResponse $response) {
        $coin_id = $request->input('coin_id');
        $days_text = $request->input('days') ? $request->input('days') : 'max';
        switch ($days_text) {
            case "1d":
                $days = 1;
                $interval = "m1";
                break;
            case "2d":
                $days = 2;
                $interval = "m2";
                break;
            case "7d":
                $days = 7;
                $interval = "m15";
                break;
            case "30d":
                $days = 30;
                $interval = "h1";
                break;
            case "90d":
                $days = 90;
                $interval = "h1";
                break;
            case "180d":
                $days = 180;
                $interval = "h1";
                break;
            case "1y":
                $days = 365;
                $interval = "h1";
                break;

            default:
                $days = "max";
                $interval = "m30";
                break;
        }
        if (!$coin_id) {
            return $response->setError()->setCode(404)->setMessage('Not found');
        }
        $data = array();

        $cache_key = $coin_id."_prices_$days_text";
        $data = Cache::get($cache_key);
        if(empty($data)) {
            $data = array();

            //$request_url = "https://pro-api.coingecko.com/api/v3/coins/$coin_id/market_chart?x_cg_pro_api_key=CG-39XhuQdDhGGesfdaJso5aWM9&vs_currency=usd&days=$days";
            //$request_url = "https://pro-api.coingecko.com/api/v3/coins/bitcoin/ohlc?x_cg_pro_api_key=CG-39XhuQdDhGGesfdaJso5aWM9&vs_currency=usd&days=$days";

            $start = strtotime("- 14 days") * 1000;
            $end = time() * 1000;
            $request_url = "https://api.coincap.io/v2/candles?exchange=binance&interval=$interval&baseId=bitcoin&quoteId=tether&api_key=d667aa8e-fa6c-4cd9-aaee-c8694b338267&start=$start&end=$end";
            //$request_url = "https://api.coincap.io/v2/candles?exchange=binance&interval=$interval&baseId=bitcoin&quoteId=tether&api_key=d667aa8e-fa6c-4cd9-aaee-c8694b338267";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $request_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $json_decode = json_decode($response);
            $prices = $json_decode->data;
            foreach ($prices as $price) {
                /*switch ($days_text) {
                    case "1d":
                    case "2d":
                    $formated_date = date("H:i", $price[0]/1000);
                        break;

                    default:
                        $formated_date = date("Y-m-d", $price[0]/1000);
                        break;
                }*/
                /*$data[] = array(
                    'time'  => $price[0],
                    'date'  => $formated_date,
                    'price' => (float) $price[4]
                );*/
                //$data[] = [$price[0], (float) $price[4]];
                $data[] = [$price->period, (float) $price->open];
            }

            Cache::put($cache_key, $data, 60);
        }
        return response()->json($data);
    }
    public function getCoinPrices(Request $request, BaseHttpResponse $response) {
        $data = array();
        $cache_key = "coin_prices";
        $data = Cache::get($cache_key);
        if(empty($data)) {
            $data = array();

            $candles = new CoincapCandles;
            $candles = $candles::orderBy("period", "asc");
            $candles = $candles->get();
            
            foreach ($candles as $candle) {
                $data[] = [$candle->period, (float) $candle->open];
            }

            Cache::put($cache_key, $data, 60);
        }
        return response()->json($data);
    }

    public function pieChart(string $coin_id, Request $request, BaseHttpResponse $response)
    {
        if (!$coin_id) {
            return $response->setError()->setCode(404)->setMessage('Not found');
        }
        $data = array();

        $cache_key = $coin_id."_pie_chart";
        $data = Cache::get($cache_key);
        if(empty($data)) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://coingen.net/api/coingecko_pie_chart',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            $result = json_decode($response);

            $data = array(
                'inflation'          => $result->inflation,
                'token_release'      => $result->token_release,
                'block_rewards'      => $result->block_rewards,
                'burn'               => $result->burn,
                'rebated'            => 0.15,
                'circulating_supply' => $result->circulating_supply,
                'circulating_1y_ago' => $result->circulating_1y_ago,
            );
            Cache::put($cache_key, $data, 60);
        }
        return response()->json($data);
    }

    public function _coin(string $coin_id, Request $request, BaseHttpResponse $response)
    {
        if (!$coin_id) {
            return $response->setError()->setCode(404)->setMessage('Not found');
        }
        $data = array();

        $cache_key = $coin_id."_history";
        $data = Cache::get($cache_key);
        if(empty($data)) {
            $data = array();
            for ($i = 1; $i <= 12; $i++) {
                $last_date_in_month =  date("t-m-Y", strtotime( date( 'Y-m-01' )." -$i months"));
                $month_name =  date("M-Y", strtotime( date( 'Y-m-01' )." -$i months"));

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.coingecko.com/api/v3/coins/'.$coin_id.'/history?date='.$last_date_in_month.'&localization=en',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                ));
                $response = curl_exec($curl);
                curl_close($curl);

                $result = json_decode($response);
                $current_price = $result->market_data->current_price->usd;
                $market_cap = $result->market_data->market_cap->usd;
                $total_volume = $result->market_data->total_volume->usd;

                $data[] = array(
                    'in_month'      => $last_date_in_month,
                    'label'         => $month_name,
                    'current_price' => (float) $current_price,
                    'market_cap'    => (float) $market_cap,
                    'total_volume'  => (float) $total_volume,
                );
            }
            Cache::put($cache_key, $data, 60);
        }
        return response()->json($data);
    }

    private function curl_get($url, $proxy = "") {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($proxy) curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-type: application/x-www-form-urlencoded"
        ));
        $ip = $_SERVER['SERVER_ADDR'];
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
        $result = curl_exec($ch);
        if(curl_errno($ch) !== 0) {
            die('cURL error when connecting to ' . $url . ': ' . curl_error($ch));
        }
        curl_close($ch);
        $data = json_decode($result);
        return $data;
    }

    private function token_release($coin_id) {
        $today = date("Y-m-d");
        $date = date("Y-m-d", strtotime($today." -1 year"));
        $request_uri = "https://pro-api.coingecko.com/api/v3/coins/$coin_id?localization=false&tickers=false&community_data=false&developer_data=false&sparkline=false&x_cg_pro_api_key=CG-39XhuQdDhGGesfdaJso5aWM9";
        $request_uri = "https://api.coingecko.com/api/v3/coins/$coin_id?localization=false&tickers=false&community_data=false&developer_data=false&sparkline=false";
        $coin = $this->curl_get($request_uri);
        $market_caps_today = isset($coin->market_data->market_cap->usd) ? $coin->market_data->market_cap->usd : 0;
        $price_today = isset($coin->market_data->current_price->usd) ? $coin->market_data->current_price->usd : 0;
        $coin_circulating_supply = isset($coin->market_data->circulating_supply) ? $coin->market_data->circulating_supply : 0;

        $start    = (new \DateTime($date))->modify('first day of this month');
        $end      = (new \DateTime($today))->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);
        $total    = 0;
        foreach ($period as $dt) {
            $last_date_in_month = $dt->format("Y-m-t");

            $row = CoinDaily::where([['coin_id','=',$coin_id],['date','=',$last_date_in_month]])->first();
            $circulating_supply = isset($row->circulating_supply) ? $row->circulating_supply : 0;

            $prev = (new \DateTime($last_date_in_month))->modify('last day of previous month');
            $prev_date = $prev->format("Y-m-t");

            $prev_row = CoinDaily::where([['coin_id','=',$coin_id],['date','=',$prev_date]])->first();
            $prev_circulating_supply = isset($prev_row->circulating_supply) ? $prev_row->circulating_supply : 0;

            $amount_release = abs($circulating_supply - $prev_circulating_supply);

            if(date("Y-m") === $dt->format("Y-m")) {
                $amount_release = $coin_circulating_supply - $prev_circulating_supply;
            }

            $total = $total + $amount_release;
        }
        return $total;

    }

    public function getCoins(string $coin_id, Request $request, BaseHttpResponse $response)
    {
        if (!$coin_id) {
            return $response->setError()->setCode(404)->setMessage('Not found');
        }
        $data = array();

        $cache_key = $coin_id."_info";
        $data = Cache::get($cache_key);
        if(empty($data)) {
            $data = array();

            $request_uri = "https://pro-api.coingecko.com/api/v3/coins/$coin_id?localization=false&tickers=false&community_data=true&developer_data=false&sparkline=false&x_cg_pro_api_key=".$this->x_cg_pro_api_key;
            $request_uri = "https://api.coingecko.com/api/v3/coins/$coin_id?localization=false&tickers=false&developer_data=false&sparkline=false";
            $coin = $this->curl_get($request_uri);

            $coin_row = CoinContract::where('coin_id', $coin_id)->first();
            $date = date("Y-m-d");
            $data_hub = CoinDatahub::where([['protocol', '=', $coin_id],['date','=',$date]]);
            $total_stake = isset($data_hub->total_stake) ? $data_hub->total_stake : 0;

            $_coin_object = $this->curl_get("https://pro.coingen.net/api/v3/coin_id/$coin_id");
            if(!$total_stake) {
                $total_stake = $_coin_object->stake;
            }

            $total_supply = isset($coin->market_data->total_supply) ? $coin->market_data->total_supply : $coin_row->total_supply;
            $max_supply = isset($coin->market_data->max_supply) ? $coin->market_data->max_supply : $coin_row->max_supply;
            $circulating_supply = isset($coin->market_data->circulating_supply) ? $coin->market_data->circulating_supply : $coin_row->circulating_supply;
            $total_stake_pt = $total_supply > 0 ? $total_stake / $total_supply * 100 : 0;

            $last_coin = CoinDaily::where([['coin_id', '=', $coin_id],['date','=',$date]])->first();
            $inflation = isset($last_coin->inflation) ? $last_coin->inflation : 0;
            $inflation_pt = isset($last_coin->inflation_pt) ? $last_coin->inflation_pt : 0;

            $token_release_pt = 89.5;
            $block_rewards_pt = isset($_coin_object->block_rewards) ? $_coin_object->block_rewards : 0;
            $burn_pt = 0.7;
            $rebated_pt = 0.3;

            //$sum = $token_release_pt + $block_rewards_pt + $burn_pt + $rebated_pt;

            $token_release = $inflation * ($token_release_pt/100);

            $last_year_coin = CoinDaily::where([['coin_id', '=', $coin_id],['date','=',date("Y-m-d", strtotime("-1 year"))]])->first();
            if($last_year_coin) {
                $last_year_circulating_supply = isset($last_year_coin->circulating_supply) ? $last_year_coin->circulating_supply : 0;
                $token_release = $circulating_supply - $last_year_circulating_supply;
            }
            if($coin_id == "aptos") {
                $token_release = $circulating_supply;
            }
            $block_rewards = $total_stake * $block_rewards_pt / 100;
            //$block_rewards = $inflation * ($block_rewards_pt/100);

            $burn = $inflation * ($burn_pt/100);
            $burn = $_coin_object->burn;
            $rebated = $inflation * ($rebated_pt/100);

            if($coin_id == "aptos") {
                $totalsupply = curl_get("https://api.aptoscan.com/api?module=stats&action=totalsupply");
                $total_supply = isset($totalsupply->result) ? substr($totalsupply->result, 0, 10) : 0;
                //$block_rewards = $total_supply - 1000000000;
            }

            if($token_release <= 0) {
                $token_release = $_coin_object->token_release;
            }

            $token_release = $this->token_release($coin_id);

            /*if($block_rewards <= 0) {
                $block_rewards = $_coin_object->block_rewards;
            }*/
            if($burn <= 0) {
                $burn = $_coin_object->burn;
            }
            if($rebated <= 0) {
                $rebated = $_coin_object->rebated;
            }

            $messari_coin_id = $coin_row->symbol;
            if($coin_id == "bitcoin") {
                $messari_coin_uri = $coin_id;
            }

            $messari_coin_uri = "https://data.messari.io/api/v2/assets/".$messari_coin_id."/profile?x-messari-api-key=df6545ea-787b-437a-b152-5a71b4d1e09b";
            $messari_coin = $this->curl_get($messari_coin_uri);

            $coin_data = $this->coinRepository->getFirstBy(array("coin_id" => $coin_id));

            $technology = isset($messari_coin->data->profile->technology->overview->technology_details) ? $messari_coin->data->profile->technology->overview->technology_details : null;
            $individuals = isset($messari_coin->data->profile->contributors->individuals) ? $messari_coin->data->profile->contributors->individuals : null;
            $roadmap = isset($messari_coin->data->profile->general->roadmap) ? $messari_coin->data->profile->general->roadmap : null;
            $orientation = isset($messari_coin->data->profile->general->background->background_details) ? $messari_coin->data->profile->general->background->background_details : null;
            $assets = isset($messari_coin->data->profile->ecosystem->assets) ? $messari_coin->data->profile->ecosystem->assets : null;
            $organizations = isset($messari_coin->data->profile->ecosystem->organizations) ? $messari_coin->data->profile->ecosystem->organizations : null;
            $community_data = isset($coin->community_data) ? $coin->community_data : null;
            $fundraising = isset($messari_coin->data->profile->economics->launch->fundraising) ? $messari_coin->data->profile->economics->launch->fundraising : null;
            $launch_style = isset($messari_coin->data->profile->economics->launch->general->launch_style) ? $messari_coin->data->profile->economics->launch->general->launch_style : null;
            $launch_details = isset($messari_coin->data->profile->economics->launch->general->launch_details) ? $messari_coin->data->profile->economics->launch->general->launch_details : null;
            $initial_distribution = isset($messari_coin->data->profile->economics->launch->initial_distribution) ? $messari_coin->data->profile->economics->launch->initial_distribution : null;
            $sector = isset($messari_coin->data->profile->general->overview->sector) ? $messari_coin->data->profile->general->overview->sector : null;
            $consensus = isset($messari_coin->data->profile->economics->consensus_and_emission->consensus) ? $messari_coin->data->profile->economics->consensus_and_emission->consensus : null;
            $investors = isset($messari_coin->data->profile->investors) ? $messari_coin->data->profile->investors : null;
            $supply = isset($messari_coin->data->profile->economics->consensus_and_emission->supply) ? $messari_coin->data->profile->economics->consensus_and_emission->supply : null;

            $technology = $coin_data->technology;
            $ecosystem = $coin_data->ecosystem ? json_decode($coin_data->ecosystem) : NULL;
            $ecosystem_arr = array();
            foreach ($ecosystem as $row_obj) {
                $image_link = $row_obj[0]->value;
                if(filter_var($image_link, FILTER_VALIDATE_URL)) {
                    $image_src = $image_link;
                } else {
                    $image_src = RvMedia::getImageUrl($image_link);
                }
                $ecosystem_arr[] = array(
                    $row_obj[0]->key => $image_src,
                    $row_obj[1]->key => $row_obj[1]->value,
                    $row_obj[2]->key => $row_obj[2]->value
                );
            }
            $team_backers = $coin_data->team_backers ? json_decode($coin_data->team_backers) : NULL;
            $team_backer_arr = array();
            foreach ($team_backers as $row_obj) {
                $team_backer_arr[] = array(
                    $row_obj[0]->key => $row_obj[0]->value,
                    $row_obj[1]->key => $row_obj[1]->value,
                    $row_obj[2]->key => $row_obj[2]->value
                );
            }
            $investors = $coin_data->investors ? json_decode($coin_data->investors) : NULL;
            $investor_arr = array();
            foreach ($investors as $row_obj) {
                $investor_arr[] = array(
                    $row_obj[0]->key => $row_obj[0]->value,
                    $row_obj[1]->key => $row_obj[1]->value,
                    $row_obj[2]->key => $row_obj[2]->value
                );
            }
            $community_data = $coin_data->community_data;
            $orientation = $coin_data->orientation;
            $roadmap = $coin_data->roadmap ? json_decode($coin_data->roadmap) : NULL;
            $roadmap_arr = array();
            foreach ($roadmap as $row_obj) {
                $roadmap_arr[] = array(
                    $row_obj[0]->key => $row_obj[0]->value,
                    $row_obj[1]->key => $row_obj[1]->value,
                    $row_obj[2]->key => $row_obj[2]->value,
                    $row_obj[3]->key => $row_obj[3]->value
                );
            }

            $fund_raising = $coin_data->fund_raising ? json_decode($coin_data->fund_raising) : NULL;
            $fund_raising_arr = array();
            if($fund_raising) {
                foreach ($fund_raising as $row_obj) {
                    $fund_raising_arr[] = array(
                        $row_obj[0]->key => $row_obj[0]->value,
                        $row_obj[1]->key => $row_obj[1]->value,
                        $row_obj[2]->key => (float) $row_obj[2]->value,
                        $row_obj[3]->key => (float) $row_obj[3]->value
                    );
                }
            }

            $messari_coin_uri = "https://data.messari.io/api/v1/assets/".$messari_coin_id."/metrics?x-messari-api-key=df6545ea-787b-437a-b152-5a71b4d1e09b";
            $messari_coin = $this->curl_get($messari_coin_uri);
            $annual_inflation_percent = isset($messari_coin->data->supply->annual_inflation_percent) ? $messari_coin->data->supply->annual_inflation_percent : 0;

            if($max_supply > 0) {
                $sum = $max_supply;
            } else {
                $sum = $total_supply;
            }
            $total_supply_pt = $total_supply * 100 / $sum;
            $circulating_supply_pt = $circulating_supply * 100 / $sum;
            $stake_pt = $total_stake * 100 / $sum;

            $data = array(
                'id'                          => $coin_id,
                'symbol'                      => $coin->symbol,
                'name'                        => $coin->name,
                'price'                       => $coin->market_data->current_price->usd,
                'price_change_percentage_24h' => $coin->market_data->price_change_percentage_24h,
                'high_24h'                    => $coin->market_data->high_24h->usd,
                'low_24h'                     => $coin->market_data->low_24h->usd,
                'ath'                         => $coin->market_data->ath->usd,
                'atl'                         => $coin->market_data->atl->usd,
                'ath_change_percentage'       => $coin->market_data->ath_change_percentage->usd,
                'atl_change_percentage'       => $coin->market_data->atl_change_percentage->usd,
                'ath_date'                    => $coin->market_data->ath_date->usd,
                'exchanges'                   => isset($coin_row->exchanges) ? $coin_row->exchanges : 0,
                'exchanges_change_24h_pt'     => isset($coin_row->exchanges_change_24h_pt) ? $coin_row->exchanges_change_24h_pt : 0,
                'markets'                     => isset($coin_row->markets) ? $coin_row->markets : 0,
                'markets_change_24h_pt'       => isset($coin_row->markets_change_24h_pt) ? $coin_row->markets_change_24h_pt : 0,
                'pairs'                       => isset($coin_row->pairs) ? $coin_row->pairs : 0,
                'pairs_change_24h_pt'         => isset($coin_row->pairs_change_24h_pt) ? $coin_row->pairs_change_24h_pt : 0,
                'max_supply'                  => $max_supply,
                'total_supply'                => $total_supply,
                'total_supply_pt'             => $total_supply_pt,
                'circulating_supply'          => $circulating_supply,
                //'circulating_supply_pt'       => $total_supply > 0 ? round($circulating_supply / $total_supply * 100, 2) : 0,
                'circulating_supply_pt'       => $circulating_supply_pt,
                'stake'                       => $total_stake,
                //'stake_pt'                    => round($total_stake_pt, 2),
                'stake_pt'                    => $stake_pt,
                'inflation'                   => $inflation,
                'inflation_pt'                => $inflation_pt,
                'token_release'         => (float) $token_release,
                'token_release_pt'      => (float) round($token_release_pt, 2),
                'block_rewards'         => $block_rewards,
                'block_rewards_pt'      => (float) $block_rewards_pt,
                'burn'                  => (float) $burn,
                'burn_pt'               => (float) $burn_pt,
                'rebated'               => (float) $rebated,
                'rebated_pt'            => (float) $rebated_pt,
                'technology'            => nl2br($technology),
                'ecosystem'             => $ecosystem_arr,
                'team_backers'          => $team_backer_arr,
                'investors'             => $investor_arr,
                //'individuals'           => $individuals,
                'roadmap'               => $roadmap_arr,
                'orientation'           => nl2br($orientation),
                'assets'                => $assets,
                'organizations'         => $organizations,
                'community_data'        => $community_data,
                'fundraising'           => $fundraising,
                'fund_raising'          => $fund_raising_arr,
                'launch_style'          => $launch_style ? nl2br($launch_style) : "",
                'launch_details'        => $launch_details,
                'initial_distribution'  => $initial_distribution,
                'sector'                => $sector,
                'consensus'             => $consensus,
                'allocation'            => $coin_data->allocation,
                'supply'                => $supply,
                'general_emission_type' => isset($supply->general_emission_type) ? $supply->general_emission_type : null,
                'precise_emission_type' => isset($supply->precise_emission_type) ? $supply->precise_emission_type : null,
                'annual_inflation'      => $annual_inflation_percent,
                'performance'           => $coin_data->performance,
                'release_schedule'      => $coin_data->release_schedule,
            );


            /*
            //$request_url = "https://pro-api.coingecko.com/api/v3/coins/$coin_id/?x_cg_pro_api_key=CG-39XhuQdDhGGesfdaJso5aWM9";
            $request_url = "https://coingen.net/api/coin/$coin_id";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $request_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($response);
            */
            Cache::put($cache_key, $data, 60);
        }
        return response()->json($data);
    }

    public function getAccessTokenGoogle(Request $request)
    {
        $this->client->setAccessType('offline');
        $tokenPath = storage_path('app/token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }

        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            } else {

            }
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
        }


        $rurl = "https://account.coingen.net/api/v3/coin/google_access_token";
        $this->client->setRedirectUri($rurl);

        if (!isset($_GET['code'])) {
            $auth_url = $this->client->createAuthUrl();
            $filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
            return redirect($filtered_url);
        } else {
            $code = $request->input("code");
            $this->client->authenticate($code);
            $access_token = $this->client->getAccessToken();
            $accessToken = $access_token['access_token'];
            echo $accessToken;
            die;
        }
    }

    public function getGoogleSheet(Request $request, BaseHttpResponse $response) {
        $sheetId = "1TeAFDUUlCWVeHMYIhYatcsMf6ln193Pnhg_Ju-ZrBCk";
        $accessToken = "ya29.a0Aa4xrXN1mxhn0gD-5s-XXw8dMuV6CIDfgdf1ik1h0YCLh_iB971EvBmjxsnyk3PxJ2SLjXY3qkimL7DZC-pVmzb9iPc3TzeiML2T0FLNRnRocR3j_ZhUMB-P8XFCzEoZn-YVfqeth6Ues7M9B6qgi5L6FhqLaQaCgYKATASARMSFQEjDvL9mYfqfhjEsRiBLK_K77b8OQ0165";
        $this->client->setAccessToken($accessToken);
        $service = new \Google_Service_Sheets($this->client);

        $spreadsheetId = [$sheetId];
        $range = '2022!A1:E20';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        $data = array();
        foreach ($values as $value) {
            $data[] = array(
                "month" => isset($value[0]) ? $value[0] : null,
                "date"  => isset($value[1]) ? $value[1] : null,
                "time"  => isset($value[2]) ? $value[2] : null,
                "name"  => isset($value[3]) ? $value[3] : null,
                "pt"    => isset($value[4]) ? $value[4] : null,
            );
        }
        return response()->json($data);
    }

    public function getByCoinId(string $coin_id, Request $request, BaseHttpResponse $response) {
        if (!$coin_id) {
            return $response->setError()->setCode(404)->setMessage('Not found');
        }

        $coin_object = Coin::where('coin_id', $coin_id)->where('status', BaseStatusEnum::PUBLISHED)->first();

        $data = array(
            'id' => $coin_object->id,
            'name' => $coin_object->name,
            'symbol' => $coin_object->symbol,
            'coin_id' => $coin_object->coin_id,
            'stake' => $coin_object->stake,
            'token_release' => $coin_object->token_release,
            'block_rewards' => $coin_object->block_rewards,
            'burn' => $coin_object->burn,
            'rebated' => $coin_object->rebated,
        );
        return response()->json($data);
    }

    public function getEvents(Request $request, BaseHttpResponse $response) {
        /*
        $rows = new CoindarEvents;
        $rows = $rows::get();
        foreach ($rows as $row) {
            $date_start = $row->date_start;
            echo "$date_start -> ".date("Y-m-d H:i", strtotime($date_start));
            echo '<br>';

            $_id = new \MongoDB\BSON\ObjectID($row->_id);
            //$post_data['date_public'] = date("Y-m-d H:i", strtotime($date_public));
            $post_data['start_date'] = date("Y-m-d", strtotime($date_start));
            $post_data['start_time'] = date("H:i", strtotime($date_start));
            $post_data['date_start_sort'] = (int) date("YmdHi", strtotime($date_start));
            //CoindarEvents::where('_id', $_id)->update($post_data);
        }
        die;
        */
        $page = $request->input("page");
        $limit = 12;

        $order_by = $request->input("order_by") ? $request->input("order_by") : "date_start_sort";
        $order = $request->input("order") ? $request->input("order") : "asc";

        $events = new CoindarEvents;
        $events = $events::orderBy($order_by, $order);

        $date_range = $request->input('date_range');
        if($date_range) {
            $date_range_arr = $date_range;
            if($date_range_arr[0] && $date_range_arr[1]) {
                $filter_date_start = isset($date_range_arr[0]) ? date("Y-m-d", strtotime($date_range_arr[0])) : "";
                $filter_date_end = isset($date_range_arr[1]) ? date("Y-m-d", strtotime($date_range_arr[1])) : "";
                $params['filter_date_start'] = $filter_date_start;
                $params['filter_date_end'] = $filter_date_end;

                $events = $events->where(function ($query) use ($params) {
                    $query->where('start_date', '>=', $params['filter_date_start']);
                    $query->where('start_date', '<=', $params['filter_date_end']);
                });
            }
        } else {
            $date_start = date("Y-m-d");
            $date_end = date("Y-m-d", strtotime("+7 days"));
            $events = $events->whereBetween('start_date', [$date_start, $date_end]);
        }

        $categories = $request->input('categories');
        if($categories) {
            $events = $events->whereIn('tags', $categories);
        }
        $coins = $request->input('coins');
        if($coins) {
            $events = $events->whereIn('coin_id', $coins);
        }
        $impact = $request->input('impact');
        if($impact) {
            if(count($impact) > 1) {
                $events = $events->whereIn('important', ['true', 'false']);
            } else {
                $value = ($impact[0] == 1) ? 'true' : 'false';
                $events = $events->where('important',$value);
            }
        }

        $is_favourite = $request->input('is_favourite');
        if($is_favourite !== "false") {
            $favourite = $request->input('favourite') ? $request->input('favourite') : array();
            if(count($favourite) > 0) {
                $events = $events->whereIn('_id', $favourite);
            }
        }

        $events = $events->offset($page)->paginate($limit);
        return response()->json($events);
    }

    public function getCoingeckoTrending(Request $request, BaseHttpResponse $response) {
        $rows = new CoingeckoTrending;
        $rows = $rows::orderBy("score", "asc");

        $date = $request->input("date");
        if($date) {
            $rows = $rows->where('date', $date);
        }

        $rows = $rows->get();
        return response()->json($rows);
    }

    public function getCryptorank(Request $request, BaseHttpResponse $response) {
        $rows = new Cryptorank;
        $rows = $rows::orderBy("date", "asc");

        $date = $request->input("date");
        if($date) {
            $rows = $rows->where('date', $date);
        }

        $rows = $rows->get();
        return response()->json($rows);
    }

    public function getCoincap(Request $request, BaseHttpResponse $response) {
        $rows = new CoincapCandles;
        $rows = $rows::orderBy("period", "asc");

        $date = $request->input("date");
        if($date) {
            $rows = $rows->where('date', $date);
        }
        $interval = $request->input('interval') ? $request->input('interval') : "d1";
        $rows = $rows->where('interval', $interval);

        $rows = $rows->get();
        return response()->json($rows);
    }

    private function getPairPriceRange($pair, $interval = "24h") {
        $symbol_arr = explode("_", $pair);
        $first_symbol = $symbol_arr[0];
        $last_symbol = $symbol_arr[1];

        switch ($interval) {
            case "2d":
            case "7d":
            case "1m":
            case "3m":
            case "6m":
            case "1y":
            case "3y":
                if($interval == "1m") {
                    $date = date("Y-m-d", strtotime("-1 month"));
                } else if($interval == "3m") {
                    $date = date("Y-m-d", strtotime("-3 month"));
                } else if($interval == "6m") {
                    $date = date("Y-m-d", strtotime("-6 month"));
                } else if($interval == "1y") {
                    $date = date("Y-m-d", strtotime("-1 year"));
                } else if($interval == "3y") {
                    $date = date("Y-m-d", strtotime("-3 year"));
                } else if($interval == "2d") {
                    $date = date("Y-m-d", strtotime("-2 day"));
                } else {
                    $date = date("Y-m-d", strtotime("-7 day"));
                }

                $first_symbol_yesterday = CoinDaily::where([['symbol', '=', $first_symbol],['date', '=', $date]])->first();
                $first_price_yesterday = $first_symbol_yesterday->price;

                $last_symbol_yesterday = CoinDaily::where([['symbol', '=', $last_symbol],['date', '=', $date]])->first();
                $last_price_yesterday = $last_symbol_yesterday->price;

                return $first_price_yesterday / $last_price_yesterday;
                break;
            case "1d":
                $yesterday = date("Y-m-d", strtotime("-1 day"));
                $first_symbol_yesterday = CoinDaily::where([['symbol', '=', $first_symbol],['date', '=', $yesterday]])->first();
                $first_price_yesterday = $first_symbol_yesterday->price ? $first_symbol_yesterday->price : 0;
                $last_symbol_yesterday = CoinDaily::where([['symbol', '=', $last_symbol],['date', '=', $yesterday]])->first();
                $last_price_yesterday = $last_symbol_yesterday->price;
                return $first_price_yesterday / $last_price_yesterday;
                break;
            default:
                $today = date("Y-m-d");
                $first_symbol_today = CoinDaily::where([['symbol', '=', $first_symbol],['date', '=', $today]])->first();
                $first_price_today = $first_symbol_today->price;
                $last_symbol_today = CoinDaily::where([['symbol', '=', $last_symbol],['date', '=', $today]])->first();
                $last_price_today = $last_symbol_today->price;
                return $first_price_today / $last_price_today;
                break;
        }
    }
    public function getCoinpair(Request $request, BaseHttpResponse $response) {
        $pair = $request->input("pair");
        if (!$pair) {
            return $response->setError()->setCode(404)->setMessage('Not found');
        }

        /*
        $rows = CoinDaily::where("coin_id", "bitcoin")->get();
        foreach ($rows as $row) {
            $post_data['symbol'] = "BTC";
            $_id = new \MongoDB\BSON\ObjectID($row->_id);
            CoinDaily::where('_id', $_id)->update($post_data);
        }
        */

        $pair_price_today = $this->getPairPriceRange($pair, "1d");
        $pair_price_yesterday = $this->getPairPriceRange($pair, "2d");
        $pair_price_7d = $this->getPairPriceRange($pair, "7d");
        $pair_price_1m = $this->getPairPriceRange($pair, "1m");
        $pair_price_3m = $this->getPairPriceRange($pair, "3m");
        $pair_price_6m = $this->getPairPriceRange($pair, "6m");
        $pair_price_1y = $this->getPairPriceRange($pair, "1y");
        $pair_price_3y = $this->getPairPriceRange($pair, "3y");

        $data['pair'] = $pair;
        $data['24h'] = ($pair_price_today - $pair_price_yesterday) / $pair_price_yesterday * 100;
        $data['7d'] = ($pair_price_today - $pair_price_7d) / $pair_price_7d * 100;
        $data['1m'] = ($pair_price_today - $pair_price_1m) / $pair_price_1m * 100;
        $data['3m'] = ($pair_price_today - $pair_price_3m) / $pair_price_3m * 100;
        $data['6m'] = ($pair_price_today - $pair_price_6m) / $pair_price_6m * 100;
        $data['1y'] = ($pair_price_today - $pair_price_1y) / $pair_price_1y * 100;
        $data['3y'] = ($pair_price_today - $pair_price_3y) / $pair_price_3y * 100;

        $symbol_arr = explode("_", $pair);
        $first_symbol = $symbol_arr[0];
        $last_symbol = $symbol_arr[1];
        $yesterday = date("Y-m-d", strtotime("-1 day"));
        $first_symbol_yesterday = CoinDaily::where([['symbol', '=', $first_symbol],['date', '=', $yesterday]])->first();
        $first_price_yesterday = $first_symbol_yesterday->price;
        $data[$first_symbol.'_price'] = $first_price_yesterday;

        $last_symbol_yesterday = CoinDaily::where([['symbol', '=', $last_symbol],['date', '=', $yesterday]])->first();
        $last_price_yesterday = $last_symbol_yesterday->price;
        $data[$last_symbol.'_price'] = $last_price_yesterday;

        return response()->json($data);
    }

    public function getCoinglass(Request $request, BaseHttpResponse $response) {
        $rows = new Coinglass;
        $rows = $rows::orderBy("date", "asc");

        $date = $request->input("date");
        if($date) {
            $rows = $rows->where('date', $date);
        }

        $rows = $rows->get();
        return response()->json($rows);
    }

    public function getMessariMetrics(Request $request, BaseHttpResponse $response) {
        $rows = new MessariMetrics;
        $rows = $rows::orderBy("date", "asc");

        $date = $request->input("date");
        if($date) {
            $rows = $rows->where('date', $date);
        }

        $rows = $rows->get();
        return response()->json($rows);
    }

    public function getMigrationUser(Request $request, BaseHttpResponse $response) {
        /*$old_users = DbtUser::get();
        foreach ($old_users as $user) {
            $old_user_id = $user->user_id;
            $old_email = $user->email;

            $post_data = array(
                'referred_by'  => $user->sponsor_id,
                'affiliate_id' => $old_user_id,
                'first_name'   => $user->first_name,
                'last_name'    => $user->last_name,
                'email'        => $old_email,
                'old_password' => $user->password,
                'dob'          => $user->birthday,
                'phone'        => $user->phone,
                'confirmed_at' => $user->date_added,
                'created_at'   => $user->date_added,
                'updated_at'   => $user->date_modified,
            );

            $member = Member::where("email", $old_email)->first();
            if($member) {
                echo "<span style='color: red;'>$old_email exist</span>";
                Member::where('id', $member->id)->update($post_data);
            } else {
                echo "$old_email not exist";
                $post_data['password'] = $user->password;
                Member::create($post_data);
            }
            echo '<br>';
        }
        */
        $member = Member::whereNotNull('old_password')->where('migration_sent', 0)->first();
        $email = $member->email;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://pro.coingen.net/api/v1/password/forgot',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('email' => $email),
        ));

        $response = curl_exec($curl);

        $error = 0;
        if (curl_errno($curl)) {
            $error = 1;
        }
        curl_close($curl);

        if(!$error) {
            $post_data['migration_sent'] = 1;
            Member::where('id', $member->id)->update($post_data);
        }
    }

    public function getLlamaRaises(Request $request, BaseHttpResponse $response) {
        $rows = new LlamaRaises;
        $rows = $rows::orderBy("date", "desc");

        $date = $request->input("date");
        if($date) {
            $rows = $rows->where('date', strtotime($date));
        }

        $rows = $rows->get();
        return response()->json($rows);
    }

    public function getQuantifycryptoCoin(Request $request, BaseHttpResponse $response) {
        $rows = new QuantifycryptoCoins;
        $rows = $rows::orderBy("rank", "asc");

        $symbol = $request->input("symbol");
        if($symbol) {
            $rows = $rows->where('coin_symbol', strtoupper($symbol));
            $rows = $rows->first();
        } else {
            $rows = $rows->get();
        }
        return response()->json($rows);
    }
}
