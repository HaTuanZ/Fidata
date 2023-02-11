<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model;

class CoinCategories extends Model
{
    //protected $connection = 'mongodb';
    //protected $collection = 'coingecko_categories_daily';

    protected $connection = 'new_mongodb';
    protected $collection = 'coins_categories';
}