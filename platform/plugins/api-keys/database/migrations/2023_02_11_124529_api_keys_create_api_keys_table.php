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
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('api_keys_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('api_keys_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'api_keys_id'], 'api_keys_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_keys');
        Schema::dropIfExists('api_keys_translations');
    }
};
