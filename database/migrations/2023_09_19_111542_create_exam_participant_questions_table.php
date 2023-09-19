<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateExamParticipantQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_participant_questions', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('schedule');
            $table->uuid('participant');
            $table->longText('question')->nullable();
            $table->timestamps();

            $table->foreign('participant')->on('exam_participants')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('schedule')->on('exam_schedules')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
        });

        DB::statement("ALTER TABLE `exam_participant_questions` comment 'tabel kumpulan soal peserta'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_participant_questions');
    }
}
