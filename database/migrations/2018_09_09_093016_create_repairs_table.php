<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('location')->index();
            $table->string('name')->nullable()->comment('报修人');
            $table->string('content');
            $table->string('phone_number')->nullable();
            $table->integer('input_user_id');
            $table->string('review_user_id')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->boolean('is_passed')->nullable();
            $table->string('review_remark')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->string('finisher')->nullable()->comment('完工人');
            $table->string('finish_remark')->nullable();
            $table->string('estimate')->nullable()->comment('评价');
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
        Schema::dropIfExists('repairs');
    }
}
