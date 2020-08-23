<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->index()->comment('费用名称');
            $table->boolean('turn_in')->default(true)->index()->comment('是否上缴');
            $table->boolean('is_using')->default(true)->comment('是否在用');
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
        Schema::dropIfExists('bill_types');
    }
}
