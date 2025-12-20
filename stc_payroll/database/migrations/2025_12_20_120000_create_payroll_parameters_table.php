<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_parameters', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            // PF related fields
            $table->decimal('pf_percentage', 5, 2)->default(12.00);
            $table->decimal('ppf_percentage', 5, 2)->default(3.67);
            $table->decimal('ac_no_2_pf_percentage', 5, 2)->default(0.85);
            $table->decimal('ac_22_pf_percentage', 5, 2)->default(0.01);
            $table->decimal('epf_percentage', 5, 2)->default(8.33);
            $table->decimal('if_percentage', 5, 2)->default(0.50);
            $table->decimal('ac_21_pf_percentage', 5, 2)->default(0.50);
            
            // ESIC related fields
            $table->decimal('esic_employee_percentage', 5, 2)->default(3.25);
            $table->decimal('esic_employer_percentage', 5, 2)->default(0.75);
            $table->decimal('esic_limit', 10, 2)->default(21000.00);
            
            // Month and days
            $table->date('previous_month')->nullable();
            $table->date('current_month')->nullable();
            $table->integer('sunday')->default(0);
            $table->integer('manday')->default(0);
            $table->integer('total_days')->default(30);
            $table->decimal('holiday_percentage', 5, 2)->default(1.00);
            
            // Limits
            $table->decimal('pf_limit', 10, 2)->default(15000.00);
            
            // Dates
            $table->date('bonus_start_date')->nullable();
            $table->date('leave_start_date')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_parameters');
    }
}

