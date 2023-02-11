<?php

namespace Botble\Financial\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Deposit extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dbt_deposit';

    /**
     * @var array
     */
    protected $fillable = [
        'email_account',
        'status',
    ];

}
