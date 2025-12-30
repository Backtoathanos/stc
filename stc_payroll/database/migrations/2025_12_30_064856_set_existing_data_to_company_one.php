<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SetExistingDataToCompanyOne extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, ensure company with ID 1 exists
        $companyExists = DB::table('companies')->where('id', 1)->exists();
        
        if (!$companyExists) {
            // Create default company with ID 1
            DB::table('companies')->insert([
                'id' => 1,
                'name' => 'Default Company',
                'code' => 'DEFAULT',
                'status' => 'ACTIVE',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // Update all users to company_id = 1 (where company_id is null)
        DB::table('users')
            ->whereNull('company_id')
            ->update(['company_id' => 1]);
        
        // Update all employees to company_id = 1 (where company_id is null)
        DB::table('employees')
            ->whereNull('company_id')
            ->update(['company_id' => 1]);
        
        // Update all sites to company_id = 1 (where company_id is null)
        DB::table('sites')
            ->whereNull('company_id')
            ->update(['company_id' => 1]);
        
        // Update all departments to company_id = 1 (where company_id is null)
        DB::table('departments')
            ->whereNull('company_id')
            ->update(['company_id' => 1]);
        
        // Update all designations to company_id = 1 (where company_id is null)
        DB::table('designations')
            ->whereNull('company_id')
            ->update(['company_id' => 1]);
        
        // Update all gangs to company_id = 1 (where company_id is null)
        DB::table('gangs')
            ->whereNull('company_id')
            ->update(['company_id' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Set all company_id to null (revert)
        DB::table('users')->update(['company_id' => null]);
        DB::table('employees')->update(['company_id' => null]);
        DB::table('sites')->update(['company_id' => null]);
        DB::table('departments')->update(['company_id' => null]);
        DB::table('designations')->update(['company_id' => null]);
        DB::table('gangs')->update(['company_id' => null]);
        
        // Optionally delete the default company
        // DB::table('companies')->where('id', 1)->delete();
    }
}
