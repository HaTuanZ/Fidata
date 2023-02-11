<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model;

class CoinDatahub extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'datahub';


}