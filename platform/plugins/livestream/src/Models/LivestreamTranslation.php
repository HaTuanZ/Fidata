<?php

namespace Botble\Livestream\Models;

use Botble\Base\Models\BaseModel;

class LivestreamTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'livestreams_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'livestreams_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
