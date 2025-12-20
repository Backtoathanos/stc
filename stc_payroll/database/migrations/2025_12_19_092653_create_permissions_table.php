<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('section'); // e.g., 'Master', 'Transaction', 'Reports'
            $table->string('page'); // e.g., 'Sites', 'Departments', 'Employees'
            $table->string('operation'); // 'view', 'edit', 'delete'
            $table->string('slug')->unique(); // e.g., 'master.sites.view'
            $table->timestamps();
            
            $table->index(['section', 'page', 'operation']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
