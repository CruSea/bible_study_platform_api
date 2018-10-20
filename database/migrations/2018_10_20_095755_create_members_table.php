<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->string('phone')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->integer('age')->nullable();
            $table->string('sex')->nullable();  //M for male or F for female
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('school')->nullable();
            $table->string('stream')->nullable();
            $table->integer('is_confirmed')->nullable()->default(0);
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
        Schema::dropIfExists('members');
    }
}
