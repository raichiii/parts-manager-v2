<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 6)->unique()->comment('JOBナンバー');
            $table->string('name', 127)->comment('JOB名');
            $table->integer('requester_id')->unsigned()->nullable()->comment('依頼元(会社)');
            $table->integer('delivery_destination_id')->unsigned()->nullable()->comment('納入先(会社)');
            $table->string('description', 255)->nullable()->commnet('詳細');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('requester_id')->references('id')->on('companies')->onDelete('restrict');
            $table->foreign('delivery_destination_id')->references('id')->on('companies')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
