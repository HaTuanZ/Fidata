<?php

namespace Botble\Coin\Commands;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Coin\Repositories\Interfaces\CoinInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Botble\Coin\Models\Coin;

class CoingeckoCoinsCommand extends Command {

	/**
	 * @var CoinInterface
	 */
	public $coinRepository;

	protected $signature = 'coin:coingecko-coins';

	protected $description = 'Get coins list from Coingecko';

	public function __construct(CoinInterface $coinRepository)
	{
		parent::__construct();
		$this->coinRepository = $coinRepository;
	}

	public function handle() {
		$endpoint = "https://api.coingecko.com/api/v3/coins/list";
		$client = new \GuzzleHttp\Client();
		$response = $client->request('GET', $endpoint);
		$statusCode = $response->getStatusCode();
		if($statusCode == 200) {
			$coins = json_decode($response->getBody());
			foreach ($coins as $coin) {
				$coin_id = $coin->id;
				$symbol = $coin->symbol;
				if($coin_id && $symbol) {
					$post_data = array(
						'name'    => $coin->name,
						'coin_id' => $coin_id,
						'symbol'  => $symbol,
					);
					$coin_first = Coin::where([['coin_id', '=', $coin_id], ['symbol', '=', $symbol]])->first();
					if($coin_first) {
						$coin_first->update($post_data);
					} else {
                        $post_data = array_merge($post_data, array("status" => "pending"));
						Coin::create($post_data);
					}
				} else {
					Log::warning($coin->name." invalid.");
				}
			}
		}
	}
}
