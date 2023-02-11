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
        Schema::create('gems', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
	        $table->integer('paid');
	        $table->integer('bonus')->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('gems_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('gems_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'gems_id'], 'gems_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gems');
        Schema::dropIfExists('gems_translations');
    }
};
