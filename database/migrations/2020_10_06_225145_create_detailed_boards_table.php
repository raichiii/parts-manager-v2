<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDetailedBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detailed_boards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('初期担当');
            $table->integer('mounted_board_id')->unsigned();
            $table->enum('hardware_operation', ['OK', 'Unknown', 'NG'])->comment('HW動作');
            $table->enum('adjustment_calibration', ['OK', 'None', 'Unknown', 'NG'])->comment('調整校正');
            $table->enum('shipment', ['Yet', 'Use', 'Done'])->comment('出荷');
            $table->string('hardware_version', 15)->nullable()->comment('HWバージョン');
            $table->string('software_version', 15)->nullable()->comment('SWバージョン');
            $table->string('check_sum', 15)->nullable()->comment('チェックサム');
            $table->integer('lot_amount')->nullable()->default(0)->unsigned();
            $table->string('depository', 15)->nullable()->comment('保管場所');
            $table->text('note')->nullable()->comment('備考');
            $table->date('production_date')->nullable();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('mounted_board_id')->references('id')->on('mounted_boards')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detailed_boards');
    }
}
