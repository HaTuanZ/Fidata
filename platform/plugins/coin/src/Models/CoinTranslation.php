<?php

namespace Botble\Coin\Models;

use Botble\Base\Models\BaseModel;

class CoinTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coins_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'coins_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
