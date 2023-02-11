<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Coinglass extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'coinglass';

    protected $fillable = [
        'date',
        'number',
        'shortRate',
        'longRate',
        'longVolUsd',
        'rate',
        'shortVolUsd',
        'exchangeName',
        'totalVolUsd',
        'averagePrice',
        'exchangeLogo'
    ];

}