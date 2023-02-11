<?php

namespace Botble\Coin\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Coin extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coins';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
	    'coin_id',
	    'symbol',
	    'image',
	    'market_cap_rank',
	    'coingecko_rank',
	    'current_price',
	    'total_value_locked',
	    'roi',
	    'ath',
	    'ath_change_percentage',
	    'ath_date',
	    'atl',
	    'atl_change_percentage',
	    'atl_date',
	    'market_cap',
	    'fully_diluted_valuation',
	    'total_volume',
	    'price_change_24h',
	    'price_change_percentage_24h',
	    'price_change_percentage_7d',
	    'price_change_percentage_14d',
	    'price_change_percentage_30d',
	    'price_change_percentage_60d',
	    'price_change_percentage_200d',
	    'price_change_percentage_1y',
	    'market_cap_change_24h',
	    'market_cap_change_percentage_24h',
	    'total_supply',
	    'max_supply',
	    'circulating_supply',
	    'description',
        'status',
        'stake',
        'token_release',
        'block_rewards',
        'burn',
        'rebated',
        'technology',
        'ecosystem',
        'team_backers',
        'investors',
        'community_data',
        'community',
        'roadmap',
        'orientation',
        'initial_supply',
        'investors1',
        'investors2',
        'investors3',
        'launch_style',
        'launch_details',
        'fund_raising',
        'performance',
        'release_schedule',
        'allocation'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
