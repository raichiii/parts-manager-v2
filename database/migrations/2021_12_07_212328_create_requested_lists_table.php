<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestedListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requested_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_id')->unsigned()->comment('Job');
            $table->integer('thing_id')->unsigned()->nullable()->comment('商品');
            $table->integer('lot_size')->unsigned()->comment('入り数');
            $table->integer('lot_amount')->unsigned()->comment('袋数');
            $table->tinyInteger('lot_unit_id')->unsigned()->comment('袋単位');
            $table->integer('supplier_id')->unsigned()->nullable()->comment('仕入れ先');
            $table->integer('unit_price')->unsigned()->nullable()->comment('袋単価');
            $table->string('depository', 31)->nullable()->comment('棚');
            $table->dateTime('purchase_at')->nullable()->comment('希望納期');
            $table->integer('requested_user_id')->unsigned()->comment('購入依頼者');
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('購入依頼日');
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('thing_id')->references('id')->on('things')->onDelete('restrict');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('restrict');
            $table->foreign('lot_unit_id')->references('id')->on('lot_units')->onDelete('restrict');
            $table->foreign('supplier_id')->references('id')->on('companies')->onDelete('restrict');
            $table->foreign('requested_user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requested_lists');
    }
}
