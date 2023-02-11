<?php

namespace Botble\Coin\Commands;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Coin\Repositories\Interfaces\CoinInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Botble\Coin\Models\Coin;

class CoingeckoCoinCommand extends Command {

	/**
	 * @var CoinInterface
	 */
	public $coinRepository;

	protected $signature = 'coin:coingecko-coin';

	protected $description = 'Get single coin from Coingecko';

	protected $api_key = "";

	public function __construct(CoinInterface $coinRepository)
	{
		parent::__construct();
		$this->coinRepository = $coinRepository;
		$this->api_key = "CG-39XhuQdDhGGesfdaJso5aWM9";
	}

	public function handle() {
		$coins = Coin::where('status', 'published')->orderBy('coingecko_rank', 'ASC')->offset(0)->limit(25)->get();
		//$coins = Coin::where('status', 'published')->orderBy('coingecko_rank', 'ASC')->get();
		foreach ($coins as $coin) {
			$this->coin($coin);
		}

		$coins = Coin::where('status', 'published')->orderBy('coingecko_rank', 'ASC')->offset(25)->limit(100)->get();
		foreach ($coins as $coin) {
			$this->coin($coin);
		}

		$coins = Coin::where('status', 'published')->orderBy('coingecko_rank', 'ASC')->offset(125)->get();
		foreach ($coins as $coin) {
			$this->coin($coin);
		}
	}

	public function coin($coin) {
		$coin_id = $coin['coin_id'];
		$endpoint = "https://api.coingecko.com/api/v3/coins/{$coin_id}?tickers=false&market_data=true&community_data=true&developer_data=true&sparkline=false&api_key=".$this->api_key;
		$client = new \GuzzleHttp\Client();
		$response = $client->request('GET', $endpoint);
		$statusCode = $response->getStatusCode();
		if($statusCode == 200) {
			$response_json = json_decode($response->getBody());
			$post_data = array(
				'image' => isset($response_json->image->large) ? $response_json->image->large : NULL,
				'market_cap_rank' => isset($response_json->market_cap_rank) ? $response_json->market_cap_rank : NULL,
				'coingecko_rank' => isset($response_json->coingecko_rank) ? $response_json->coingecko_rank : NULL,
				'current_price' => isset($response_json->market_data->current_price->usd) ? $response_json->market_data->current_price->usd : NULL,
				'total_value_locked' => isset($response_json->market_data->total_value_locked->usd) ? $response_json->market_data->total_value_locked->usd : NULL,
				'roi' => isset($response_json->market_data->roi->percentage) ? $response_json->market_data->roi->percentage : NULL,
				'ath' => isset($response_json->market_data->ath->usd) ? $response_json->market_data->ath->usd : NULL,
				'ath_change_percentage' => isset($response_json->market_data->ath_change_percentage->usd) ? $response_json->market_data->ath_change_percentage->usd : NULL,
				'ath_date' => isset($response_json->market_data->ath_date->usd) ? $response_json->market_data->ath_date->usd : NULL,
				'atl' => isset($response_json->market_data->atl->usd) ? $response_json->market_data->atl->usd : NULL,
				'atl_change_percentage' => isset($response_json->market_data->atl_change_percentage->usd) ? $response_json->market_data->atl_change_percentage->usd : NULL,
				'atl_date' => isset($response_json->market_data->atl_date->usd) ? $response_json->market_data->atl_date->usd : NULL,
				'market_cap' => isset($response_json->market_data->market_cap->usd) ? $response_json->market_data->market_cap->usd : NULL,
				"fully_diluted_valuation" => isset($response_json->market_data->fully_diluted_valuation->usd) ? $response_json->market_data->fully_diluted_valuation->usd : NULL,
				"total_volume" => isset($response_json->market_data->total_volume->usd) ? $response_json->market_data->total_volume->usd : NULL,
				"price_change_24h" => isset($response_json->market_data->price_change_24h) ? $response_json->market_data->price_change_24h : NULL,
				"price_change_percentage_24h" => isset($response_json->market_data->price_change_percentage_24h) ? $response_json->market_data->price_change_percentage_24h : NULL,
				"price_change_percentage_7d" => isset($response_json->market_data->price_change_percentage_7d) ? $response_json->market_data->price_change_percentage_7d : NULL,
				"price_change_percentage_14d" => isset($response_json->market_data->price_change_percentage_14d) ? $response_json->market_data->price_change_percentage_14d : NULL,
				"price_change_percentage_30d" => isset($response_json->market_data->price_change_percentage_30d) ? $response_json->market_data->price_change_percentage_30d : NULL,
				"price_change_percentage_60d" => isset($response_json->market_data->price_change_percentage_60d) ? $response_json->market_data->price_change_percentage_60d : NULL,
				"price_change_percentage_200d" => isset($response_json->market_data->price_change_percentage_200d) ? $response_json->market_data->price_change_percentage_200d : NULL,
				"price_change_percentage_1y" => isset($response_json->market_data->price_change_percentage_1y) ? $response_json->market_data->price_change_percentage_1y : NULL,
				"market_cap_change_24h" => isset($response_json->market_data->market_cap_change_24h) ? $response_json->market_data->market_cap_change_24h : NULL,
				"market_cap_change_percentage_24h" => isset($response_json->market_data->market_cap_change_percentage_24h) ? $response_json->market_data->market_cap_change_percentage_24h : NULL,
				"total_supply" => isset($response_json->market_data->total_supply) ? $response_json->market_data->total_supply : NULL,
				"max_supply" => isset($response_json->market_data->max_supply) ? $response_json->market_data->max_supply : NULL,
				"circulating_supply" => isset($response_json->market_data->circulating_supply) ? $response_json->market_data->circulating_supply : NULL,
				'description' => isset($response_json->description->en) ? $response_json->description->en : NULL,
			);
			//Log::info($post_data);
			$coin->update($post_data);
			Log::info($coin['symbol'].":".(isset($response_json->market_data->current_price->usd) ? $response_json->market_data->current_price->usd : NULL));
		} else {
			Log::warning($response->getBody());
		}
	}
}