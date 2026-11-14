<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('thing_id')->unsigned()->unique()->comment('部品');
            $table->string('name', 127)->comment('部品名');
            $table->string('short_name', 63)->nullable()->comment('略名');
            $table->string('english_name', 127)->nullable()->comment('英語名');
            $table->string('pno', 31)->nullable()->comment('PNO');
            $table->string('p', 31)->nullable()->comment('P');
            $table->string('common', 31)->nullable()->comment('共通');
            $table->string('classification', 31)->nullable()->comment('分類');
            $table->string('status', 31)->nullable()->comment('ステータス');
            $table->string('interchange', 127)->nullable()->comment('互換');
            $table->string('alternative', 127)->nullable()->comment('代替');
            $table->string('note', 255)->nullable()->comment('備考');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('thing_id')->references('id')->on('things')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parts');
    }
}
