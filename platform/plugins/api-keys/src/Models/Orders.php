<?php

namespace Botble\ApiKeys\Models;

use Botble\ACL\Models\User;
use Botble\Base\Models\BaseModel;

class Orders extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'symbol',
        'orderId',
        'orderListId',
        'clientOrderId',
        'price',
        'origQty',
        'executedQty',
        'cummulativeQuoteQty',
        'status',
        'timeInForce',
        'type',
        'side',
        'stopPrice',
        'icebergQty',
        'time',
        'updateTime',
        'isWorking',
        'workingTime',
        'origQuoteOrderQty',
        'selfTradePreventionMode'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
