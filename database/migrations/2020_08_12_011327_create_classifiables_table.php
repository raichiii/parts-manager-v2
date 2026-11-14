<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateClassifiablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifiables', function (Blueprint $table) {
            $table->tinyInteger('classification_id')->unsigned();
            $table->integer('classifiable_id')->unsigned();
            $table->string('classifiable_type', 127);

            $table->unique(['classification_id', 'classifiable_id', 'classifiable_type'], 'classifiables_unique');
            $table->foreign('classification_id')->references('id')->on('classifications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classifiables');
    }
}
