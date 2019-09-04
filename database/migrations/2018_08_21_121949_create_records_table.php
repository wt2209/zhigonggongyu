<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Record;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id')->index();
            $table->integer('room_id')->index();
            $table->integer('type_id')->index();
            $table->date('record_at')->default(Record::DEFAULT_RECORD_DATE)->comment('本条记录的入住日期');
            $table->date('start_at')->nullable()->index()->comment('住房开始日');
            $table->date('end_at')->nullable()->index()->comment('合同结束日');
            $table->tinyInteger('status')->default(Record::STATUS_NULL);
            $table->integer('to_room_id')->nullable()->comment('调整到房间id');
            $table->softDeletes()->comment('退房/调房日期');
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
        Schema::dropIfExists('records');
    }
}
