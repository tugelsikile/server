<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetMajorNullableToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign('courses_major_foreign');
            $table->uuid('major')->nullable()->change();
            $table->foreign('major')->on('majors')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign('courses_major_foreign');
            $table->uuid('major')->change();
            $table->foreign('major')->on('majors')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }
}
