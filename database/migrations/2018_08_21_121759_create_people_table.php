<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Person;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->index();
            $table->string('short_name')->nullable()->index();
            $table->string('serial')->nullable()->index()->comment('工号');
            $table->string('identify')->nullable()->unique();
            $table->enum('gender', ['男', '女'])->default('男');
            $table->string('department')->nullable();
            $table->date('entered_at')->default(Person::DEFAULT_ENTER_DATE)->nullable();
            $table->date('contract_start')->default(Person::CONTRACT_DEFAULT_START)->nullable();
            $table->date('contract_end')->default(Person::CONTRACT_DEFAULT_END)->nullable();
            $table->tinyInteger('education')->default(Person::EDUCATION_UNKNOWN);
            $table->string('phone_number')->index()->nullable();
            $table->string('remark')->nullable();
            $table->string('spouse_name')->index()->nullable()->comment('配偶姓名');
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
        Schema::dropIfExists('people');
    }
}
