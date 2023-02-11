<?php

namespace Botble\Package\Models;

use Botble\Base\Models\BaseModel;

class PackageTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'packages_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'packages_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
