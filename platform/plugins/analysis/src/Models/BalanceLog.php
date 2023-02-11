<?php

namespace Botble\Analysis\Models;

use Botble\Base\Models\BaseModel;

class BalanceLog extends BaseModel
{
    protected $table = 'dbt_balance_log';

    protected $fillable = [
        "log_id",
        "ref_id",
        "balance_id",
        "user_id",
        "prev_balance",
        "currency_symbol",
        "operator",
        "transaction_type",
        "transaction_amount",
        "transaction_fees",
        "note",
        "ip_address",
    ];
}