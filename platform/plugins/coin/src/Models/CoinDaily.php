<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model;

class CoinDaily extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'coins_daily';
}