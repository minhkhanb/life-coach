<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('full_name')->nullable();
            // $table->string('first_name')->nullable();
            // $table->string('last_name')->nullable();
            $table->tinyInteger('gender')->default(0)->comment('0: female, 1: male');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('identity_card')->nullable()->comment('số cmnd');
            // $table->string('link_affiliate')->nullable();
            $table->tinyInteger('active')->default(1)->comment('0: un_active, 1: active');
            $table->tinyInteger('type')->default('1')->comment('1:ADMIN, 2: COACH, 3: STUDENT');
            $table->string('nick_fb')->nullable();
            $table->string('email_fb')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->dateTime('date_join_sys')->nullable()->comment('ngày join hệ thống');
            $table->integer('user_owner')->nullable()->comment('Người quản lý hoc vien');
            $table->string('image')->nullable()->comment('ảnh người dùng');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
