<?php

return [
    [
        'name' => 'Gems',
        'flag' => 'gem.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'gem.create',
        'parent_flag' => 'gem.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'gem.edit',
        'parent_flag' => 'gem.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'gem.destroy',
        'parent_flag' => 'gem.index',
    ],
];
