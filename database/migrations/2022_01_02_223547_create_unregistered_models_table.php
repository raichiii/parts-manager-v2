
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnregisteredModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unregistered_models', function (Blueprint $table) {
            $table->id();
            $table->string('name', 63)->comment('名前/型番');
            $table->enum('type', ['ModelNo', 'Supplier'])->comment('未登録タイプ');
            $table->integer('listable_id')->unsigned()->comment('リスト');
            $table->string('listable_type', 63)->comment('依頼タイプ');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->unique(['type', 'listable_id', 'listable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unregistered_models');
    }
}
