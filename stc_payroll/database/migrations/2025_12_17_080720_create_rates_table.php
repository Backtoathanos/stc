<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->decimal('ctc', 15, 2)->default(0);
            $table->decimal('basic', 15, 2)->default(0);
            $table->decimal('da', 15, 2)->default(0);
            $table->decimal('arate', 15, 2)->default(0);
            $table->decimal('otrate', 15, 2)->default(0);
            $table->decimal('hra', 15, 2)->default(0);
            $table->decimal('madical', 15, 2)->default(0);
            $table->decimal('ExgratiaRetention', 15, 2)->default(0);
            $table->decimal('LTARetention', 15, 2)->default(0);
            $table->decimal('LTA', 15, 2)->default(0);
            $table->decimal('CA', 15, 2)->default(0);
            $table->decimal('Fooding', 15, 2)->default(0);
            $table->decimal('Misc', 15, 2)->default(0);
            $table->decimal('CEA', 15, 2)->default(0);
            $table->decimal('WashingAllowance', 15, 2)->default(0);
            $table->decimal('ProfessionalPursuits', 15, 2)->default(0);
            $table->decimal('SpecialAllowance', 15, 2)->default(0);
            $table->decimal('IncomeTax', 15, 2)->default(0);
            $table->decimal('personalpay', 15, 2)->default(0);
            $table->decimal('petrol', 15, 2)->default(0);
            $table->decimal('mobile', 15, 2)->default(0);
            $table->decimal('incentive', 15, 2)->default(0);
            $table->decimal('fixedamt', 15, 2)->default(0);
            $table->decimal('netamt', 15, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rates');
    }
}
