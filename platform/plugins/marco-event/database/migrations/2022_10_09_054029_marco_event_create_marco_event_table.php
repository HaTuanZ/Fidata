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
        Schema::create('marco_events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->date('event_date');
            $table->time('event_time', 2)->nullable();
            $table->time('actual', 5, 2)->nullable();
            $table->time('forecast', 5, 2)->nullable();
            $table->time('previous',5, 2)->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('marco_events_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('marco_events_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'marco_events_id'], 'marco_events_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marco_events');
        Schema::dropIfExists('marco_events_translations');
    }
};
