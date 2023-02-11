<?php

namespace Botble\MarcoEvent\Models;

use Botble\Base\Models\BaseModel;

class MarcoEventTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'marco_events_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'marco_events_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
