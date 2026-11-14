<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individuals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 63)->comment('名前');
            $table->string('furigana', 63)->nullable()->comment('ふりがな');
            $table->string('english_name', 127)->nullable()->comment('英語名');
            $table->string('postalcode', 8)->nullable()->comment('郵便番号');
            $table->tinyInteger('prefecture_id')->unsigned()->nullable()->comment('都道府県');
            $table->string('address', 127)->comment('住所');
            $table->json('phones')->nullable()->comment('電話番号');
            $table->json('faxes')->nullable()->comment('FAX');
            $table->json('emails')->nullable()->comment('メールアドレス');
            $table->json('urls')->nullable()->comment('URL');
            $table->text('note')->nullable()->comment('備考');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();

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
        Schema::dropIfExists('individuals');
    }
}
