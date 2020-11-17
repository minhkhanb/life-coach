<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_student_detail', function (Blueprint $table) {
            $table->tinyInteger('status')->nullable()->after('answer')->comment('0:Ko dat,1:Dat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_student_detail', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
