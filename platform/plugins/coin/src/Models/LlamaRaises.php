<?php

namespace Botble\Coin\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class LlamaRaises extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'llama_raises';

    protected $fillable = [
        'date',
        'name',
        'round',
        'amount',
        'chains',
        'sector',
        'category',
        'leadInvestors',
        'otherInvestors',
        'valuation',
    ];

}