<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id')->unsigned();
            $table->string('darbs');
            $table->bigInteger('daritaja_id')->unsigned();
            $table->date('datums');
            $table->string('ilgums')->default('1=0;2=0;3=0;4=0;5=0;6=0;7=0;');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('daritaja_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data');
    }
}
