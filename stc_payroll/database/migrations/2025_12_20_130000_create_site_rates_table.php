<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('site_id');
            $table->string('category', 50); // UN-SKILLED, SEMI-SKILLED, SKILLED, HIGH-SKILLED
            $table->decimal('basic', 10, 2);
            $table->decimal('da', 10, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->unique(['site_id', 'category']); // One rate per site-category combination
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_rates');
    }
}

