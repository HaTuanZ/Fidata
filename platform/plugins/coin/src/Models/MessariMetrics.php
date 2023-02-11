<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class MessariMetrics extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'messari_metrics';

    protected $fillable = [
        'date',
        'hour',
        'timestamp',
        'coin_id',
        'serial_id',
        'symbol',
        'name',
        'slug',
        'price_usd',
        'volume_last_24_hours',
        'percent_change_usd_last_1_hour',
        'percent_change_usd_last_24_hours',
        'ohlcv_last_1_hour',
        'ohlcv_last_24_hour',
        'percent_change_last_1_week',
        'percent_change_last_1_month',
        'percent_change_last_3_months',
        'percent_change_last_1_year',
        'roi_by_year',
    ];

}