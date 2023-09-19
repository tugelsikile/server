<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->integer('number')->default(1);
            $table->uuid('course_topic');
            $table->longText('content');
            $table->string('type',32)->default('multi_choice')->comment('enum values = multi_choice,string,lines,absolute');
            $table->double('max_score',20,2)->default(1);
            $table->double('min_score',20,2)->default(1);
            $table->timestamps();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();

            $table->index('id');
            $table->index('number');
            $table->index('course_topic');
            $table->index('type');

            $table->foreign('course_topic')->on('course_topics')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('created_by')->on('users')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('updated_by')->on('users')->references('id')->nullOnDelete()->cascadeOnUpdate();
        });

        DB::statement("ALTER TABLE `questions` comment 'tabel soal'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
