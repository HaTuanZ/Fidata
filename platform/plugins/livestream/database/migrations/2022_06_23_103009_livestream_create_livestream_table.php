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
        Schema::create('livestreams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
	        $table->string('description', 255)->nullable();
	        $table->integer('user_id');
	        $table->longText('embled')->nullable();
	        $table->dateTime('event_date')->nullable();
	        $table->string('thumbnail', 255)->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('livestreams_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('livestreams_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'livestreams_id'], 'livestreams_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('livestreams');
        Schema::dropIfExists('livestreams_translations');
    }
};
