<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->string('name', 127)->comment('営業所名');
            $table->string('postalcode', 8)->nullable()->comment('郵便番号');
            $table->tinyInteger('prefecture_id')->nullable()->unsigned();
            $table->string('address', 127)->comment('住所');
            $table->string('building', 63)->nullable()->comment('ビル名');
            $table->json('phones')->nullable()->comment('電話番号');
            $table->json('faxes')->nullable()->comment('FAX');
            $table->json('emails')->nullable()->comment('メールアドレス');
            $table->text('note')->nullable()->comment('備考');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict');
            $table->foreign('prefecture_id')->references('id')->on('prefectures')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offices');
    }
}
