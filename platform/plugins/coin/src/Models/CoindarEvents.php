<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class CoindarEvents extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'coindar_events';

    protected $fillable = [
        'caption',
        'source',
        'source_reliable',
        'important',
        'date_public',
        'date_start',
        'date_end',
        'start_date',
        'start_time',
        'date_start_sort',
        'coin_id',
        'coin',
        'coin_price_changes',
        'tags',
        'tag_name',
    ];

}