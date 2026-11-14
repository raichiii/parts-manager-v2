<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMountedBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mounted_boards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('print_circuit_board_id')->unsigned();
            $table->string('version_name', 127)->nullable()->comment('基板のバージョン名');
            $table->smallInteger('version')->unsigned()->comment('基板のバージョン');
            $table->string('description', 255)->nullable()->comment('詳細');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();

            $table->unique(['print_circuit_board_id', 'version_name', 'version'], 'print_circuit_board_unique');
            $table->foreign('print_circuit_board_id')->references('id')->on('print_circuit_boards')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mounted_boards');
    }
}
