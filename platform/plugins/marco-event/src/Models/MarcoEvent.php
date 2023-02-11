<?php

namespace Botble\MarcoEvent\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class MarcoEvent extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'marco_events';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'event_date',
        'event_time',
        'actual',
        'forecast',
        'previous',
        'type',
        'color',
        'image'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
