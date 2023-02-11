<?php

return [
    [
        'name' => 'Livestreams',
        'flag' => 'livestream.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'livestream.create',
        'parent_flag' => 'livestream.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'livestream.edit',
        'parent_flag' => 'livestream.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'livestream.destroy',
        'parent_flag' => 'livestream.index',
    ],
];
