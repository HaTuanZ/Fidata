<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class CoindarTags extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'coindar_tags';

    protected $fillable = [
        'id',
        'name',
    ];

}