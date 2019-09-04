<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('room_id')->index();
            $table->integer('from_type_id')->index();
            $table->integer('to_type_id')->index();
            $table->tinyInteger('from_person_number');
            $table->tinyInteger('to_person_number');
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
        Schema::dropIfExists('type_histories');
    }
}
