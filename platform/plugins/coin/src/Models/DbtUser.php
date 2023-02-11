<?php

namespace Botble\Coin\Models;

use Botble\Base\Models\BaseModel;

class DbtUser extends BaseModel
{
    protected $table = 'dbt_user';
    protected $connection = 'mysql2';
}