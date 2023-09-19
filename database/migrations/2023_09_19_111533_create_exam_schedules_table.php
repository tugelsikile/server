<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateExamSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('client');
            $table->string('name');
            $table->string('token',32)->nullable();
            $table->timestamps();
            $table->dateTime('active_at')->nullable();

            $table->index('id');
            $table->index('client');
            $table->index('active_at');
            $table->foreign('client')->on('exam_clients')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });

        DB::statement("ALTER TABLE `exam_schedules` comment 'tabel jadwal ujian'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_schedules');
    }
}
