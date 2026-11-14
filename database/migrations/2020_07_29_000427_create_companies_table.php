<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 127)->unique()->comment('会社名');
            $table->string('furigana', 255)->nullable()->comment('会社のふりがな');
            $table->string('short_name', 63)->nullable()->comment('呼称');
            $table->string('english_name', 255)->nullable()->comment('会社名(英語)');
            $table->string('short_english_name', 127)->nullable()->comment('呼称の英語名');
            $table->enum('type', ['Kabushiki', 'Goudou', 'Goumei',  'Goushi', 'Yuugen'])->nullable()->comment('会社の種類');
            $table->enum('type_side', ['mae', 'ato'])->nullable()->comment('表記の前後');
            $table->json('urls')->nullable()->comment('会社のURL');
            $table->text('note')->nullable()->comment('備考');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
