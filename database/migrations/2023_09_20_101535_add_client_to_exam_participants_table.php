<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientToExamParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_participants', function (Blueprint $table) {
            $table->uuid('client')->after('exam');
            $table->foreign('client')->on('exam_clients')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_participants', function (Blueprint $table) {
            $table->dropForeign('exam_participants_client_foreign');
            $table->dropColumn('client');
        });
    }
}
