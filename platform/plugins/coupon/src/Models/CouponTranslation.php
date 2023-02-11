<?php

namespace Botble\Coupon\Models;

use Botble\Base\Models\BaseModel;

class CouponTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coupons_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'coupons_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
