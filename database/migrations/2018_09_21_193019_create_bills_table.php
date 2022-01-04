<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->string('location')->index()->comment('位置/房间号');
            $table->string('name')->nullable()->index();
            $table->integer('bill_type_id');
            // 为保证每一笔费用的独立性，特把 是否上缴 字段保留下来
            $table->boolean('turn_in')->default(true)->comment('是否上缴');
            $table->decimal('cost')->comment('费用');
            $table->boolean('is_refund')->default(false)->comment('是否是退费');
            $table->string('explain')->nullable()->comment('费用说明');
            $table->string('remark')->nullable()->comment('备注');
            $table->integer('input_user_id')->nullable()->comment('录入人');
            $table->timestamp('payed_at')->index()->nullable()->comment('缴费时间');
            $table->string('charge_mode')->nullable()->comment('缴费方式');
            $table->integer('pay_user_id')->nullable()->comment('缴费操作人');
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
        Schema::dropIfExists('bills');
    }
}
