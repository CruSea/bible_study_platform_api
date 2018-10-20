<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bs_id')->unsigned()->nullable();
            $table->integer('member_id')->unsigned()->nullable();
            $table->dateTime('date')->nullable();
            $table->string('full_name')->nullable();
            $table->string('imei')->nullable();
            $table->string('city')->nullable();
            $table->string('school')->nullable();
            $table->string('comment')->nullable();

            $table->timestamps();
        });


        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('bs_id')->references('id')->on('user_roles');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('member_id')->references('id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
