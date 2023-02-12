<?php

namespace Botble\ApiKeys\Models;

use Botble\ACL\Models\User;
use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

class ApiKeys extends BaseModel
{
    use EnumCastable;
    const ADMINISTRATOR_ROLE = 'administrator';
    const AUTHOR_ROLE = 'author';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'api_keys';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'api_key',
        'api_key_secret',
        'user_id',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($table) {
            $request = app('request');
            $user = $request->user();
            if (isset($user)) {
                $table->user_id = $user->id;
            }
        });
        self::addGlobalScope('user-scope', function (Builder $builder) {
            $auth = request()->user();
            if ($auth && $auth->inRole(self::AUTHOR_ROLE)) {
                $builder->where('user_id', $auth->id);
            }
        });
    }
}
