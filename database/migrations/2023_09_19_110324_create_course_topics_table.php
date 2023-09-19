<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCourseTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_topics', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('course');
            $table->string('code',32);
            $table->string('name');
            $table->timestamps();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();

            $table->index('id');
            $table->index('course');
            $table->index('code');
            $table->foreign('course')->on('courses')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('created_by')->on('users')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('updated_by')->on('users')->references('id')->nullOnDelete()->cascadeOnUpdate();
        });

        DB::statement("ALTER TABLE `course_topics` comment 'tabel kumpulan group soal'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_topics');
    }
}
