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
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('date');
            $table->uuid('uuid');
            $table->string('token');
            $table->enum('status', [
                'Agendada',
                'Realizada Paga',
                'Realizada Não Paga',
                'Cancelada',
            ]);
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('user_id');

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
        Schema::dropIfExists('appointments');
    }
};
