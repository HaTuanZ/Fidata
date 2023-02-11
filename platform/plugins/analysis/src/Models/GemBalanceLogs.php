<?php

namespace Botble\Analysis\Models;

use Botble\Base\Models\BaseModel;

class GemBalanceLogs extends BaseModel
{
    protected $table = 'gem_balance_logs';

    protected $fillable = [
        "ref_id",
        "balance_id",
        "user_id",
        "balance",
        "prev_balance",
        "currency_symbol",
        "operator",
        "note",
        "ip_address",
        "date"
    ];
}