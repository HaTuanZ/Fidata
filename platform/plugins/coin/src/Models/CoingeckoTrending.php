<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class CoingeckoTrending extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'coingecko_trending';

    protected $fillable = [
        'date',
        'id',
        'coin_id',
        'name',
        'symbol',
        'market_cap_rank',
        'thumb',
        'small',
        'large',
        'slug',
        'price_btc',
        'score',
    ];

}