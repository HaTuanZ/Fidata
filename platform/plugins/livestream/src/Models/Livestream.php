<?php

namespace Botble\Livestream\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Livestream extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'livestreams';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
	    'description',
	    'user_id',
	    'embled',
	    'video_url',
	    'gem',
	    'event_date',
	    'event_time',
	    'event_date_time',
	    'event_datetime',
	    'end_date',
	    'end_time',
	    'thumbnail',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
