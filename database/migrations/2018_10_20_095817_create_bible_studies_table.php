<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBibleStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bible_studies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bs_id');
            $table->string('term')->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('year_id')->unsigned()->nullable();
            $table->string('bs_name')->nullable();
            $table->string('title')->nullable();
            $table->string('aim')->nullable();  //M for male or F for female
            $table->string('verse')->nullable();
            $table->string('question')->nullable();
            $table->string('remark')->nullable();
            $table->string('further_info')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();
        });

        Schema::table('bible_studies', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::table('bible_studies', function (Blueprint $table) {
            $table->foreign('year_id')->references('id')->on('bs_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bible_studies');
    }
}
