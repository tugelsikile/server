<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateExamClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_clients', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('exam');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('token',32);
            $table->longText('meta')->nullable();
            $table->timestamps();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();

            $table->index('id');
            $table->index('exam');
            $table->index('code');
            $table->foreign('exam')->on('exams')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('created_by')->on('users')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('updated_by')->on('users')->references('id')->nullOnDelete()->cascadeOnUpdate();
        });

        DB::statement("ALTER TABLE `exam_clients` comment 'tabel server client / cluster'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_clients');
    }
}
