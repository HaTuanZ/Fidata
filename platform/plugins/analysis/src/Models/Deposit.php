<?php

namespace Botble\Analysis\Models;

use Botble\Base\Models\BaseModel;

class Deposit extends BaseModel
{
    protected $table = 'dbt_deposit';

    protected $fillable = [
        'id', 'order_no', 'user_id', 'method_id', 'wallet_id', 'network', 'address', 'amount', 'currency_symbol', 'fees', 'email_account', 'gem_id', 'gem_received', 'note', 'comment', 'approved_cancel_by', 'deposit_ip', 'created_at', 'modified_at', 'status'
    ];
}
