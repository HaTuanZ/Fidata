<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class CoindarCoins extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'coindar_coins';

    protected $fillable = [
        'id',
        'name',
        'symbol',
        'image_32',
        'image_64',
    ];

}