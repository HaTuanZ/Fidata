<?php

namespace Botble\ApiKeys\Jobs;

use Binance\API;
use Binance\RateLimiter;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Botble\ApiKeys\Models\Orders;

class GetOrder implements ShouldQueue
{
    protected $userId;
    protected $apiKey;
    protected $apiKeySecret;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $apiKey, $apiKeySecret)
    {
        $this->userId = $userId;
        $this->apiKey = $apiKey;
        $this->apiKeySecret = $apiKeySecret;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $api = new API($this->apiKey, $this->apiKeySecret);
        $symbols = $api->exchangeInfo()['symbols'];
        $params = [
            'startTime' => Carbon::now()->subMonths(3)->startOfMonth()->getTimestampMs()
        ];
        foreach ($symbols as $symbol => $data) {
            $orders = $api->orders($symbol, 1000, 0, $params);
            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    $order['user_id'] = $this->userId;
                    Orders::create($order);
                }
            }
            usleep(0.5 * 1000000);
        }
    }

    public function timeout()
    {
        return null;
    }
}
