<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarLeaveTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_leave_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('leave_type', 50); // NH, CL, SL, etc.
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Index for faster queries
            $table->index('date');
            $table->index(['date', 'leave_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_leave_types');
    }
}

