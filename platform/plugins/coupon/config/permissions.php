<?php

return [
    [
        'name' => 'Coupons',
        'flag' => 'coupon.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'coupon.create',
        'parent_flag' => 'coupon.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'coupon.edit',
        'parent_flag' => 'coupon.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'coupon.destroy',
        'parent_flag' => 'coupon.index',
    ],
	[
		'name'        => 'Applied',
		'flag'        => 'coupon.applied',
		'parent_flag' => 'coupon.index',
	],
];
