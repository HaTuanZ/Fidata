<?php

return [
    [
        'name' => 'Coins',
        'flag' => 'coin.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'coin.create',
        'parent_flag' => 'coin.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'coin.edit',
        'parent_flag' => 'coin.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'coin.destroy',
        'parent_flag' => 'coin.index',
    ],
];
