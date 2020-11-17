<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApproveProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approve_process', function (Blueprint $table) {
            $table->id();
            $table->integer('approve_id')->comment('ID admin phê duyệt');
            $table->integer('coach_id')->comment('ID coach');
            $table->string('images')->nullable()->comment('hình ảnh cmnd');
            $table->tinyInteger('status')->default(0)->comment('0:Waiting, 1: Approved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approve_process');
    }
}
