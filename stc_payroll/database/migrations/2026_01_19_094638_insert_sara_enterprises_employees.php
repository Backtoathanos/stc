<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertSaraEnterprisesEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companyId = 2;
        
        // Verify company exists
        $companyExists = DB::table('companies')->where('id', $companyId)->exists();
        if (!$companyExists) {
            throw new \Exception("Company with ID {$companyId} does not exist. Please create Sara Enterprises company first.");
        }
        
        // Get or create default site for company 2
        $site = DB::table('sites')->where('company_id', $companyId)->first();
        if (!$site) {
            $siteId = DB::table('sites')->insertGetId([
                'company_id' => $companyId,
                'name' => 'Default Site',
                'under_contract' => null,
                'natureofwork' => null,
                'workorderno' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $siteId = $site->id;
        }
        
        // Get or create default department for company 2
        $department = DB::table('departments')->where('company_id', $companyId)->first();
        if (!$department) {
            $departmentId = DB::table('departments')->insertGetId([
                'company_id' => $companyId,
                'name' => 'Default Department',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $departmentId = $department->id;
        }
        
        // Get or create default designation for company 2
        $designation = DB::table('designations')->where('company_id', $companyId)->first();
        if (!$designation) {
            $designationId = DB::table('designations')->insertGetId([
                'company_id' => $companyId,
                'name' => 'Default Designation',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $designationId = $designation->id;
        }
        
        // Get or create default gang for company 2
        $gang = DB::table('gangs')->where('company_id', $companyId)->first();
        if (!$gang) {
            $gangId = DB::table('gangs')->insertGetId([
                'company_id' => $companyId,
                'name' => 'Default Gang',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $gangId = $gang->id;
        }
        
        // Employee data from the image
        $employees = [
            ['EmpId' => '481426718143', 'Name' => 'BIJU KUANR'],
            ['EmpId' => '674677332791', 'Name' => 'BUDDHESWAR KUMAR'],
            ['EmpId' => '291902083391', 'Name' => 'MD SAJJAD HUSSAIN'],
            ['EmpId' => '891169306509', 'Name' => 'MD SHAHID 1'],
            ['EmpId' => '800050482795', 'Name' => 'MRIGANKA SHEKHAR GOPE'],
            ['EmpId' => '457026080201', 'Name' => 'NAWAZ SARIPH ANSARI'],
            ['EmpId' => '516376994301', 'Name' => 'SHADAB AKHTAR'],
            ['EmpId' => '541129316757', 'Name' => 'SK MD RAJIBUL HAQUE'],
            ['EmpId' => '445507069195', 'Name' => 'TAPAS MAL'],
        ];
        
        // Insert employees
        foreach ($employees as $employee) {
            // Check if employee with this EmpId already exists for this company
            $existing = DB::table('employees')
                ->where('company_id', $companyId)
                ->where('EmpId', $employee['EmpId'])
                ->first();
            
            if (!$existing) {
                DB::table('employees')->insert([
                    'company_id' => $companyId,
                    'site_id' => $siteId,
                    'department_id' => $departmentId,
                    'designation_id' => $designationId,
                    'gang_id' => $gangId,
                    'EmpId' => $employee['EmpId'],
                    'Name' => $employee['Name'],
                    'Status' => 'ACTIVE',
                    'AllowAsPer' => true,
                    'is_employee' => true,
                    'is_supervisor' => false,
                    'is_officeStaff' => false,
                    'leave_balance' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $companyId = 2;
        
        // Employee IDs to remove
        $empIds = [
            '481426718143',
            '674677332791',
            '291902083391',
            '891169306509',
            '800050482795',
            '457026080201',
            '516376994301',
            '541129316757',
            '445507069195',
        ];
        
        // Delete employees
        DB::table('employees')
            ->where('company_id', $companyId)
            ->whereIn('EmpId', $empIds)
            ->delete();
    }
}
