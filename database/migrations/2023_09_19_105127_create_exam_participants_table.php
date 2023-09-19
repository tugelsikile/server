<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateExamParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_participants', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('exam');
            $table->uuid('user');
            $table->string('user_code',32);
            $table->timestamps();

            $table->index('id');
            $table->index('exam');
            $table->index('user');
            $table->foreign('exam')->on('exams')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('user')->on('users')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
        });

        DB::statement("ALTER TABLE `exam_participants` comment 'tabel peserta ujian pada server'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_participants');
    }
}
