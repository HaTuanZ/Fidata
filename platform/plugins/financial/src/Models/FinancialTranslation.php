<?php

namespace Botble\Financial\Models;

use Botble\Base\Models\BaseModel;

class FinancialTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'financials_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'financials_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
