<?php

namespace Botble\Package\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Package extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'packages';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
	    'price',
	    'description',
	    'content',
	    'slug',
	    'access_length',
	    'access_length_amount',
	    'access_length_period',
	    'access_start_date',
	    'access_end_date',
	    'image',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
