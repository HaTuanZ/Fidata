<?php

namespace Botble\RequestLog\Models;

use Botble\Base\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Query\Builder;

class RequestLog extends BaseModel
{
    use MassPrunable;

    /**
     * @var string
     */
    protected $table = 'request_logs';

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'url',
        'status_code',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'referrer' => 'json',
        'user_id'  => 'json',
    ];

    /**
     * @return Builder
     */
    public function prunable(): Builder
    {
        return $this->whereDate('created_at', '>', Carbon::now()->subDays(30)->toDateString());
    }
}
