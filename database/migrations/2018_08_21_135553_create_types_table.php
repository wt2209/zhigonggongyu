<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->tinyInteger('fee_type')->default(\App\Models\Type::FEE_TYPE_TOTAL)->comment('水电费');
            $table->boolean('has_contract')->default(0)->comment('是否有合同期限');
            $table->boolean('has_rent_fee')->default(0)->comment('是否收取租金');
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
        Schema::dropIfExists('types');
    }
}
