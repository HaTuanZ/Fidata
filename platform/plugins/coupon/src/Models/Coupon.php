<?php

namespace Botble\Coupon\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Coupon extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coupons';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
	    'discount_type',
	    'coupon_amount',
	    'expiry_date',
	    'usage_limit_per_user',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
