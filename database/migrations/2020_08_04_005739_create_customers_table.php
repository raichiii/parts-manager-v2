<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('office_id')->unsigned();
            $table->string('name', 63)->comment('顧客名');
            $table->string('furigana', 63)->nullable()->comment('ふりがな');
            $table->string('english_name', 127)->nullable()->comment('顧客名(英語)');
            $table->string('department', 63)->nullable()->comment('部署');
            $table->string('position', 63)->nullable()->comment('肩書き');
            $table->json('phones')->nullable()->comment('電話番号');
            $table->json('faxes')->nullable()->comment('FAX');
            $table->json('emails')->nullable()->comment('メールアドレス');
            $table->text('note')->nullable()->comment('備考');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('office_id')->references('id')->on('offices')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
