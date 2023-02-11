<?php

namespace Botble\Blog\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Account extends BaseModel
{
	use EnumCastable;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * @var array
	 */
	protected $fillable = [
		'first_name',
		'last_name',
	];

	/**
	 * @var array
	 */
	protected $casts = [
		
	];
}
