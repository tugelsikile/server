<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('major');
            $table->string('code',32);
            $table->integer('level')->default(10);
            $table->timestamps();

            $table->foreign('major')->on('majors')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });

        DB::statement("ALTER TABLE `courses` comment 'tabel mata pelajaran'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
