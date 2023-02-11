<?php

namespace Botble\Gem\Models;

use Botble\Base\Models\BaseModel;

class GemTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gems_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'gems_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
