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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
	        $table->integer('price');
	        $table->string('description', 400)->nullable();
	        $table->string('content', 400)->nullable();
	        $table->string('slug', 60);
	        $table->string('access_length', 25)->nullable();
	        $table->integer('access_length_amount')->nullable();
	        $table->string('access_length_period', 25)->nullable();
	        $table->date('access_start_date')->nullable();
	        $table->date('access_end_date')->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('packages_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('packages_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'packages_id'], 'packages_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
        Schema::dropIfExists('packages_translations');
    }
};
