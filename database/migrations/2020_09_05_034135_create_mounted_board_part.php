<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMountedBoardPart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mounted_board_part', function (Blueprint $table) {
            $table->integer('mounted_board_id')->unsigned();
            $table->integer('part_id')->unsigned();
            $table->string('reference_number', 63)->nullable()->comment('参照番号');
            $table->boolean('is_mounted')->default(false)->comment('実装');

            $table->unique(['mounted_board_id', 'part_id', 'reference_number'], 'mounted_board_part_unique');
            $table->foreign('mounted_board_id')->references('id')->on('mounted_boards')->onDelete('cascade');
            $table->foreign('part_id')->references('id')->on('parts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mounted_board_part');
    }
}
