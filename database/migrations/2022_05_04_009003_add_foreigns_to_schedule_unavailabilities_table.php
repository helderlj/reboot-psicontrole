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
        Schema::table('schedule_unavailabilities', function (Blueprint $table) {
            $table
                ->foreign('schedule_id')
                ->references('id')
                ->on('schedules')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_unavailabilities', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
        });
    }
};
