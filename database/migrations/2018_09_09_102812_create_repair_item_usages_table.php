<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepairItemUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair_item_usages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('repair_id');
            $table->integer('repair_item_id');
            // 为保证单价不随items表的变动而变动，特意加上单价
            $table->decimal('price');
            $table->integer('total')->comment('用量');
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('repair_item_usages');
    }
}
