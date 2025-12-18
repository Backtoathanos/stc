<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('designation_id');
            $table->unsignedBigInteger('gang_id');
            $table->date('Dob')->nullable();
            $table->date('Doj')->nullable();
            $table->date('Doe')->nullable();
            $table->date('SafetyCardExpiry')->nullable();
            $table->string('Imageurl')->nullable();
            $table->string('EmpId');
            $table->string('Name');
            $table->string('Father')->nullable();
            $table->string('Gender')->nullable();
            $table->string('MaritalStatus')->nullable();
            $table->boolean('PfApplicable')->default(false);
            $table->string('Uan')->nullable();
            $table->boolean('EsicApplicable')->default(false);
            $table->string('Esic')->nullable();
            $table->boolean('PRFTax')->default(false);
            $table->string('Mobile')->nullable();
            $table->string('Email')->nullable();
            $table->string('EmpSafetyCard')->nullable();
            $table->text('Address')->nullable();
            $table->boolean('AttendAllow')->default(false);
            $table->boolean('OtAppl')->default(false);
            $table->boolean('MrOtAppl')->default(false);
            $table->boolean('AllowAsPer')->default(true);
            $table->boolean('ReversePF')->default(false);
            $table->string('Bank')->nullable();
            $table->string('Branch')->nullable();
            $table->string('Ifsc')->nullable();
            $table->string('Ac')->nullable();
            $table->string('Aadhar')->nullable();
            $table->string('Pan')->nullable();
            $table->string('Otslave')->nullable();
            $table->string('Ottype')->nullable();
            $table->string('Paymentmode')->nullable();
            $table->string('Weekoff')->nullable();
            $table->string('Skill')->nullable();
            $table->string('Status')->default('ACTIVE');
            $table->boolean('is_employee')->default(false);
            $table->boolean('is_supervisor')->default(false);
            $table->boolean('is_officeStaff')->default(false);
            $table->timestamps();
            
            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('designation_id')->references('id')->on('designations');
            $table->foreign('gang_id')->references('id')->on('gangs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
