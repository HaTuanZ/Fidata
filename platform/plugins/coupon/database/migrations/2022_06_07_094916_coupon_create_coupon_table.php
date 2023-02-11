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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
	        $table->string('discount_type', 25)->default('fixed_product');
	        $table->float('coupon_amount', 8, 2)->nullable();
	        $table->date('expiry_date')->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('coupons_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('coupons_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'coupons_id'], 'coupons_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('coupons_translations');
    }
};
