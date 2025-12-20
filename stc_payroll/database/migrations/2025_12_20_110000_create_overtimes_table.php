<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('aadhar', 20); // Employee Aadhar number
            $table->string('employee_name', 255); // Employee name
            $table->string('month_year', 7); // Format: YYYY-MM (e.g., 2025-01)
            
            // Days 1-31 (nullable, stores hours worked 0-8)
            $table->tinyInteger('day_1')->nullable();
            $table->tinyInteger('day_2')->nullable();
            $table->tinyInteger('day_3')->nullable();
            $table->tinyInteger('day_4')->nullable();
            $table->tinyInteger('day_5')->nullable();
            $table->tinyInteger('day_6')->nullable();
            $table->tinyInteger('day_7')->nullable();
            $table->tinyInteger('day_8')->nullable();
            $table->tinyInteger('day_9')->nullable();
            $table->tinyInteger('day_10')->nullable();
            $table->tinyInteger('day_11')->nullable();
            $table->tinyInteger('day_12')->nullable();
            $table->tinyInteger('day_13')->nullable();
            $table->tinyInteger('day_14')->nullable();
            $table->tinyInteger('day_15')->nullable();
            $table->tinyInteger('day_16')->nullable();
            $table->tinyInteger('day_17')->nullable();
            $table->tinyInteger('day_18')->nullable();
            $table->tinyInteger('day_19')->nullable();
            $table->tinyInteger('day_20')->nullable();
            $table->tinyInteger('day_21')->nullable();
            $table->tinyInteger('day_22')->nullable();
            $table->tinyInteger('day_23')->nullable();
            $table->tinyInteger('day_24')->nullable();
            $table->tinyInteger('day_25')->nullable();
            $table->tinyInteger('day_26')->nullable();
            $table->tinyInteger('day_27')->nullable();
            $table->tinyInteger('day_28')->nullable();
            $table->tinyInteger('day_29')->nullable();
            $table->tinyInteger('day_30')->nullable();
            $table->tinyInteger('day_31')->nullable();
            
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('aadhar');
            $table->index('month_year');
            $table->index(['aadhar', 'month_year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtimes');
    }
}

