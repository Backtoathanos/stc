<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStcDbTableLogTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('stc_db_table_log')) {
            return;
        }

        Schema::create('stc_db_table_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0);
            $table->string('user_name')->default('');
            $table->string('action', 32)->default('');
            $table->string('table_name', 128)->nullable();
            $table->mediumText('sql_text')->nullable();
            $table->text('detail')->nullable();
            $table->dateTime('created_at');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stc_db_table_log');
    }
}
