<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepairItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('材料名');
            $table->string('feature')->comment('规格/特点');
            $table->decimal('price')->comment('单价');
            $table->string('unit')->comment('单位');
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
        Schema::dropIfExists('repair_items');
    }
}
