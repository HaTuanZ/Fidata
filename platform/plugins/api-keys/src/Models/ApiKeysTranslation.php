<?php

namespace Botble\ApiKeys\Models;

use Botble\Base\Models\BaseModel;

class ApiKeysTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'api_keys_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'api_keys_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
