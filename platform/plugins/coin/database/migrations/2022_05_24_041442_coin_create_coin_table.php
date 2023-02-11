<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
	        $table->string('symbol', 25);
	        $table->string('coin_id', 140)->nullable();
	        $table->string('image', 255)->nullable();
	        $table->integer('market_cap_rank')->nullable();
	        $table->integer('coingecko_rank')->nullable();
	        $table->double('current_price', 15, 6)->nullable();
	        $table->double('total_value_locked', 20, 8)->nullable();
	        $table->double('roi', 15, 6)->nullable();
	        $table->double('ath', 15, 6)->nullable();
	        $table->decimal('ath_change_percentage', 5, 2)->nullable();
	        $table->dateTimeTz('ath_date')->nullable();
	        $table->double('atl', 15, 6)->nullable();
	        $table->decimal('atl_change_percentage', 5, 2)->nullable();
	        $table->dateTimeTz('atl_date')->nullable();
	        $table->double('market_cap', 20, 8)->nullable();
	        $table->double('fully_diluted_valuation', 20, 8)->nullable();
	        $table->double('total_volume', 20, 8)->nullable();
	        $table->double('high_24h', 15, 6)->nullable();
	        $table->double('low_24h', 15, 6)->nullable();
	        $table->double('price_change_24h', 10, 6)->nullable();
	        $table->decimal('price_change_percentage_24h', 5, 2)->nullable();
	        $table->decimal('price_change_percentage_7d', 5, 2)->nullable();
	        $table->decimal('price_change_percentage_14d', 5, 2)->nullable();
	        $table->decimal('price_change_percentage_30d', 5, 2)->nullable();
	        $table->decimal('price_change_percentage_60d', 5, 2)->nullable();
	        $table->decimal('price_change_percentage_200d', 5, 2)->nullable();
	        $table->decimal('price_change_percentage_1y', 5, 2)->nullable();
	        $table->double('market_cap_change_24h', 10, 6)->nullable();
	        $table->decimal('market_cap_change_percentage_24h', 5, 2)->nullable();
	        $table->double('total_supply', 15, 6)->nullable();
	        $table->double('max_supply', 15, 6)->nullable();
	        $table->double('circulating_supply', 15, 6)->nullable();
	        $table->longText('description')->nullable();
	        $table->tinyInteger('is_deposit')->default(0);
	        $table->tinyInteger('is_transfer')->default(0);
	        $table->tinyInteger('is_withdrawl')->default(0);
	        $table->tinyInteger('is_swap')->default(0);
	        $table->tinyInteger('is_trade')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('coins_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('coins_id');
            $table->string('name', 50)->nullable();
	        $table->longText('description')->nullable();

            $table->primary(['lang_code', 'coins_id'], 'coins_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coins');
        Schema::dropIfExists('coins_translations');
    }
};
