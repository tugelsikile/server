<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('question');
            $table->integer('number')->default(1);
            $table->longText('content')->nullable();
            $table->double('score',20,2)->default(1);
            $table->timestamps();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();

            $table->foreign('question')->on('questions')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('created_by')->on('users')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('updated_by')->on('users')->references('id')->nullOnDelete()->cascadeOnUpdate();
        });

        DB::statement("ALTER TABLE `answers` comment 'tabel jawaban soal'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
