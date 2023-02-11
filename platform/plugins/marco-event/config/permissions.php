<?php

return [
    [
        'name' => 'Marco events',
        'flag' => 'marco-event.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'marco-event.create',
        'parent_flag' => 'marco-event.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'marco-event.edit',
        'parent_flag' => 'marco-event.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'marco-event.destroy',
        'parent_flag' => 'marco-event.index',
    ],
];
