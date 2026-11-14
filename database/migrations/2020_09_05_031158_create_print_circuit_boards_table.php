<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePrintCircuitBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_circuit_boards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('thing_id')->unsigned()->unique()->comment('基板');
            $table->tinyInteger('valid_years')->nullable()->comment('保管年数');
            $table->string('description', 255)->nullable()->comment('詳細');
            $table->dateTime('ordered_at')->nullable()->comment('注文日');
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
        Schema::dropIfExists('print_circuit_boards');
    }
}
