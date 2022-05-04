<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_unavailabilities', function (
            Blueprint $table
        ) {
            $table->bigIncrements('id');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('schedule_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_unavailabilities');
    }
};
