<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('aadhar', 20);
            $table->string('employee_name', 255);
            $table->unsignedBigInteger('site_id');
            $table->string('month_year', 7); // Format: YYYY-MM
            $table->integer('working_days')->default(0);
            $table->integer('present_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->decimal('ot_hours', 10, 2)->default(0);
            $table->string('category', 50)->nullable(); // UN-SKILLED, SEMI-SKILLED, etc.
            
            // Rate components
            $table->decimal('basic_rate', 10, 2)->default(0);
            $table->decimal('da_rate', 10, 2)->default(0);
            
            // Calculated amounts
            $table->decimal('basic_amount', 15, 2)->default(0);
            $table->decimal('da_amount', 15, 2)->default(0);
            $table->decimal('ot_amount', 15, 2)->default(0);
            $table->decimal('gross_salary', 15, 2)->default(0);
            
            // Deductions
            $table->decimal('pf_employee', 15, 2)->default(0);
            $table->decimal('esic_employee', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            
            // Net amount
            $table->decimal('net_salary', 15, 2)->default(0);
            
            $table->timestamps();
            
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->index(['month_year', 'site_id']);
            $table->index('aadhar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
}

