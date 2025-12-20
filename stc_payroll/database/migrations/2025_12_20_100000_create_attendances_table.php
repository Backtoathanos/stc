<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('aadhar', 20); // Employee Aadhar number
            $table->string('employee_name', 255); // Employee name
            $table->string('month_year', 7); // Format: YYYY-MM (e.g., 2025-01)
            
            // Days 1-31 (nullable, can be P, O, A, or null)
            $table->string('day_1', 1)->nullable();
            $table->string('day_2', 1)->nullable();
            $table->string('day_3', 1)->nullable();
            $table->string('day_4', 1)->nullable();
            $table->string('day_5', 1)->nullable();
            $table->string('day_6', 1)->nullable();
            $table->string('day_7', 1)->nullable();
            $table->string('day_8', 1)->nullable();
            $table->string('day_9', 1)->nullable();
            $table->string('day_10', 1)->nullable();
            $table->string('day_11', 1)->nullable();
            $table->string('day_12', 1)->nullable();
            $table->string('day_13', 1)->nullable();
            $table->string('day_14', 1)->nullable();
            $table->string('day_15', 1)->nullable();
            $table->string('day_16', 1)->nullable();
            $table->string('day_17', 1)->nullable();
            $table->string('day_18', 1)->nullable();
            $table->string('day_19', 1)->nullable();
            $table->string('day_20', 1)->nullable();
            $table->string('day_21', 1)->nullable();
            $table->string('day_22', 1)->nullable();
            $table->string('day_23', 1)->nullable();
            $table->string('day_24', 1)->nullable();
            $table->string('day_25', 1)->nullable();
            $table->string('day_26', 1)->nullable();
            $table->string('day_27', 1)->nullable();
            $table->string('day_28', 1)->nullable();
            $table->string('day_29', 1)->nullable();
            $table->string('day_30', 1)->nullable();
            $table->string('day_31', 1)->nullable();
            
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
        Schema::dropIfExists('attendances');
    }
}

