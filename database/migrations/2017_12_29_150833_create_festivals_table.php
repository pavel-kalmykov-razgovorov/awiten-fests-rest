<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFestivalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('festivals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('permalink')->unique();
            $table->timestamps();
            $table->string('name');
            $table->date('date')->nullable();
            $table->string('province')->nullable();
            $table->string('location')->nullable();
            $table->string('pathLogo')->nullable();
            $table->string('pathCartel')->nullable();
            $table->integer('promoter_id')->unsigned();
            $table->foreign('promoter_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('festivals');
    }
}
