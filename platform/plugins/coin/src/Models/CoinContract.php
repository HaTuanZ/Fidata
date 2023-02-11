<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model;

class CoinContract extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'contracts';
    
}