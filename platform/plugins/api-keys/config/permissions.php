<?php

return [
    [
        'name' => 'Api keys',
        'flag' => 'api-keys.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'api-keys.create',
        'parent_flag' => 'api-keys.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'api-keys.edit',
        'parent_flag' => 'api-keys.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'api-keys.destroy',
        'parent_flag' => 'api-keys.index',
    ],
];
