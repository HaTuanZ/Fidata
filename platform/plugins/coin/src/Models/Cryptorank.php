<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Cryptorank extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'cryptorank';

    protected $fillable = [
        'date',
        'allCurrencies',
        'activeCurrencies',
        'activeMarkets',
        'totalVolume24h',
        'totalMarketCap',
        'btcDominance',
        'ethDominance',
    ];

}