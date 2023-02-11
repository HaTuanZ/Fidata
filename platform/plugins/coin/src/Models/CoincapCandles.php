<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class CoincapCandles extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'coincap_candles';

    protected $fillable = [
        'open',
        'high',
        'low',
        'close',
        'volume',
        'period',
    ];

}