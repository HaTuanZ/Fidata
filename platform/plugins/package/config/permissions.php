<?php

return [
    [
        'name' => 'Packages',
        'flag' => 'package.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'package.create',
        'parent_flag' => 'package.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'package.edit',
        'parent_flag' => 'package.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'package.destroy',
        'parent_flag' => 'package.index',
    ],
];
