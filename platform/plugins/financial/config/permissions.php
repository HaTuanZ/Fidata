<?php

return [
    [
        'name' => 'Financials',
        'flag' => 'financial.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'financial.create',
        'parent_flag' => 'financial.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'financial.edit',
        'parent_flag' => 'financial.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'financial.destroy',
        'parent_flag' => 'financial.index',
    ],
    [
        'name'        => 'Deposit',
        'flag'        => 'deposit.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'deposit.edit',
        'parent_flag' => 'deposit.index',
    ],
    [
        'name'        => 'Balance',
        'flag'        => 'balance.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'balance.edit',
        'parent_flag' => 'balance.index',
    ],
];
