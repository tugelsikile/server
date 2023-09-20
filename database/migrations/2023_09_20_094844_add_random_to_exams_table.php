<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRandomToExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->boolean('show_result')->default(true)->after('description');
            $table->boolean('random_answer')->default(false)->after('description');
            $table->boolean('random_question')->default(false)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['show_result','random_answer','random_question']);
        });
    }
}
