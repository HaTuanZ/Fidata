<?php

namespace Botble\Coin\Models;

use Botble\Base\Models\BaseModel;

class Member extends BaseModel
{
    protected $table = 'members';
    protected $connection = 'mysql';

    protected $fillable = [
        'id',
        'referred_by',
        'affiliate_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'old_password',
        'dob',
        'phone',
        'confirmed_at',
        'created_at',
        'updated_at',
    ];
}