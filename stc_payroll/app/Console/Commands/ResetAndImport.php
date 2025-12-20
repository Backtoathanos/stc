<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Site;
use App\Department;
use App\Designation;
use App\Gang;
use App\Employee;
use App\Rate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetAndImport extends Command
{
    protected $signature = 'import:reset-and-import {--confirm : Skip confirmation prompt}';
    protected $description = 'Clear all tables and re-import data from userdata.txt with exact IDs';

    public function handle()
    {
        if (!$this->option('confirm')) {
            $this->warn('WARNING: This will DELETE ALL existing data from:');
            $this->warn('  - employees');
            $this->warn('  - rates');
            $this->warn('  - sites');
            $this->warn('  - departments');
            $this->warn('  - designations');
            $this->warn('  - gangs');
            $this->warn('');
            
            if (!$this->confirm('Are you sure you want to continue?', false)) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }
        
        $this->info('Starting fresh import...');
        
        $filePath = base_path('userdata.txt');
        
        if (!file_exists($filePath)) {
            $this->error('userdata.txt file not found!');
            return 1;
        }
        
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);
        
        if (!$data || !is_array($data)) {
            $this->error('Invalid JSON format!');
            return 1;
        }
        
        $this->info('Found ' . count($data) . ' records in userdata.txt');
        
        DB::beginTransaction();
        
        try {
            // Step 1: Clear all tables (in correct order due to foreign keys)
            $this->info('Clearing existing data...');
            
            // Disable foreign key checks temporarily
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            Rate::truncate();
            Employee::truncate();
            Site::truncate();
            Department::truncate();
            Designation::truncate();
            Gang::truncate();
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->info('All tables cleared.');
            
            // Step 2: Collect all unique master data
            $this->info('Collecting master data...');
            
            $sitesMap = [];
            $departmentsMap = [];
            $designationsMap = [];
            $gangsMap = [];
            
            foreach ($data as $record) {
                // Collect Sites
                if (isset($record['SiteDetails']) && !isset($sitesMap[$record['SiteDetails']['id']])) {
                    $sitesMap[$record['SiteDetails']['id']] = [
                        'id' => $record['SiteDetails']['id'],
                        'name' => $record['SiteDetails']['name']
                    ];
                }
                
                // Collect Departments
                if (isset($record['DepartmentDetails']) && !isset($departmentsMap[$record['DepartmentDetails']['id']])) {
                    $departmentsMap[$record['DepartmentDetails']['id']] = [
                        'id' => $record['DepartmentDetails']['id'],
                        'name' => $record['DepartmentDetails']['name']
                    ];
                }
                
                // Collect Designations
                if (isset($record['DesignationDetails']) && !isset($designationsMap[$record['DesignationDetails']['id']])) {
                    $designationsMap[$record['DesignationDetails']['id']] = [
                        'id' => $record['DesignationDetails']['id'],
                        'name' => $record['DesignationDetails']['name']
                    ];
                }
                
                // Collect Gangs
                if (isset($record['GangDetails']) && !isset($gangsMap[$record['GangDetails']['id']])) {
                    $gangsMap[$record['GangDetails']['id']] = [
                        'id' => $record['GangDetails']['id'],
                        'name' => $record['GangDetails']['name']
                    ];
                }
            }
            
            // Step 3: Import master data with exact IDs
            $this->info('Importing master data...');
            
            // Import Sites
            foreach ($sitesMap as $siteData) {
                Site::create([
                    'id' => $siteData['id'],
                    'name' => $siteData['name']
                ]);
            }
            $this->info('Imported ' . count($sitesMap) . ' sites');
            
            // Import Departments
            foreach ($departmentsMap as $deptData) {
                Department::create([
                    'id' => $deptData['id'],
                    'name' => $deptData['name']
                ]);
            }
            $this->info('Imported ' . count($departmentsMap) . ' departments');
            
            // Import Designations
            foreach ($designationsMap as $desigData) {
                Designation::create([
                    'id' => $desigData['id'],
                    'name' => $desigData['name']
                ]);
            }
            $this->info('Imported ' . count($designationsMap) . ' designations');
            
            // Import Gangs
            foreach ($gangsMap as $gangData) {
                Gang::create([
                    'id' => $gangData['id'],
                    'name' => $gangData['name']
                ]);
            }
            $this->info('Imported ' . count($gangsMap) . ' gangs');
            
            // Step 4: Import Employees with exact IDs
            $this->info('Importing employees...');
            $progressBar = $this->output->createProgressBar(count($data));
            $progressBar->start();
            
            $importedEmployees = 0;
            $importedRates = 0;
            $errors = [];
            
            foreach ($data as $record) {
                try {
                    $employeeId = $record['id'] ?? null;
                    if (!$employeeId) {
                        $progressBar->advance();
                        continue;
                    }
                    
                    $siteId = $record['SiteDetails']['id'] ?? 1;
                    $deptId = $record['DepartmentDetails']['id'] ?? 1;
                    $desigId = $record['DesignationDetails']['id'] ?? 1;
                    $gangId = $record['GangDetails']['id'] ?? 1;
                    
                    $employeeData = [
                        'id' => $employeeId,
                        'site_id' => $siteId,
                        'department_id' => $deptId,
                        'designation_id' => $desigId,
                        'gang_id' => $gangId,
                        'Dob' => $record['Dob'] ?? null,
                        'Doj' => $record['Doj'] ?? null,
                        'Doe' => $record['Doe'] ?? null,
                        'SafetyCardExpiry' => $record['SafetyCardExpiry'] ?? null,
                        'Imageurl' => $record['Imageurl'] ?? null,
                        'EmpId' => $record['EmpId'] ?? '',
                        'Name' => $record['Name'] ?? '',
                        'Father' => $record['Father'] ?? null,
                        'Gender' => $record['Gender'] ?? null,
                        'MaritalStatus' => $record['MaritalStatus'] ?? null,
                        'PfApplicable' => $record['PfApplicable'] ?? false,
                        'Uan' => $record['Uan'] ?? null,
                        'EsicApplicable' => $record['EsicApplicable'] ?? false,
                        'Esic' => $record['Esic'] ?? null,
                        'PRFTax' => $record['PRFTax'] ?? false,
                        'Mobile' => $record['Mobile'] ?? null,
                        'Email' => $record['Email'] ?? null,
                        'EmpSafetyCard' => $record['EmpSafetyCard'] ?? null,
                        'Address' => $record['Address'] ?? null,
                        'AttendAllow' => $record['AttendAllow'] ?? false,
                        'OtAppl' => $record['OtAppl'] ?? false,
                        'MrOtAppl' => $record['MrOtAppl'] ?? false,
                        'AllowAsPer' => $record['AllowAsPer'] ?? true,
                        'ReversePF' => $record['ReversePF'] ?? false,
                        'Bank' => $record['Bank'] ?? null,
                        'Branch' => $record['Branch'] ?? null,
                        'Ifsc' => $record['Ifsc'] ?? null,
                        'Ac' => $record['Ac'] ?? null,
                        'Aadhar' => $record['Aadhar'] ?? null,
                        'Pan' => $record['Pan'] ?? null,
                        'Otslave' => $record['Otslave'] ?? null,
                        'Ottype' => $record['Ottype'] ?? null,
                        'Paymentmode' => $record['Paymentmode'] ?? null,
                        'Weekoff' => $record['Weekoff'] ?? null,
                        'Skill' => $record['Skill'] ?? null,
                        'Status' => $record['Status'] ?? 'ACTIVE',
                        'is_employee' => $record['is_employee'] ?? false,
                        'is_supervisor' => $record['is_supervisor'] ?? false,
                        'is_officeStaff' => $record['is_officeStaff'] ?? false,
                    ];
                    
                    // Add timestamps
                    $employeeData['created_at'] = now();
                    $employeeData['updated_at'] = now();
                    
                    // Use DB::table to insert with exact ID
                    DB::table('employees')->insert($employeeData);
                    $importedEmployees++;
                    
                    // Import Rate with exact ID from file
                    if (isset($record['rate']) && $record['rate']) {
                        $rateId = $record['rate']['id'] ?? null;
                        
                        if ($rateId) {
                            // Use employee ID from record directly
                            $rateData = [
                                'id' => $rateId,
                                'employee_id' => $employeeId,
                                'ctc' => $record['rate']['ctc'] ?? 0,
                                'basic' => $record['rate']['basic'] ?? 0,
                                'da' => $record['rate']['da'] ?? 0,
                                'arate' => $record['rate']['arate'] ?? 0,
                                'otrate' => $record['rate']['otrate'] ?? 0,
                                'hra' => $record['rate']['hra'] ?? 0,
                                'madical' => $record['rate']['madical'] ?? 0,
                                'ExgratiaRetention' => $record['rate']['ExgratiaRetention'] ?? 0,
                                'LTARetention' => $record['rate']['LTARetention'] ?? 0,
                                'LTA' => $record['rate']['LTA'] ?? 0,
                                'CA' => $record['rate']['CA'] ?? 0,
                                'Fooding' => $record['rate']['Fooding'] ?? 0,
                                'Misc' => $record['rate']['Misc'] ?? 0,
                                'CEA' => $record['rate']['CEA'] ?? 0,
                                'WashingAllowance' => $record['rate']['WashingAllowance'] ?? 0,
                                'ProfessionalPursuits' => $record['rate']['ProfessionalPursuits'] ?? 0,
                                'SpecialAllowance' => $record['rate']['SpecialAllowance'] ?? 0,
                                'IncomeTax' => $record['rate']['IncomeTax'] ?? 0,
                                'personalpay' => $record['rate']['personalpay'] ?? 0,
                                'petrol' => $record['rate']['petrol'] ?? 0,
                                'mobile' => $record['rate']['mobile'] ?? 0,
                                'incentive' => $record['rate']['incentive'] ?? 0,
                                'fixedamt' => $record['rate']['fixedamt'] ?? 0,
                                'netamt' => $record['rate']['netamt'] ?? 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                            
                            // Use DB::table to insert with exact ID
                            DB::table('rates')->insert($rateData);
                            $importedRates++;
                        }
                    }
                    
                } catch (\Exception $e) {
                    $errors[] = [
                        'employee_id' => $record['id'] ?? 'unknown',
                        'error' => $e->getMessage()
                    ];
                }
                
                $progressBar->advance();
            }
            
            $progressBar->finish();
            $this->line('');
            
            DB::commit();
            
            $this->info('');
            $this->info('=== IMPORT COMPLETED ===');
            $this->info('Total Employees: ' . Employee::count());
            $this->info('Total Rates: ' . Rate::count());
            $this->info('Total Sites: ' . Site::count());
            $this->info('Total Departments: ' . Department::count());
            $this->info('Total Designations: ' . Designation::count());
            $this->info('Total Gangs: ' . Gang::count());
            $this->info('');
            $this->info("Imported: {$importedEmployees} employees, {$importedRates} rates");
            
            if (count($errors) > 0) {
                $this->warn('Errors encountered: ' . count($errors));
                foreach (array_slice($errors, 0, 10) as $error) {
                    $this->warn("  Employee {$error['employee_id']}: {$error['error']}");
                }
                if (count($errors) > 10) {
                    $this->warn("  ... and " . (count($errors) - 10) . " more");
                }
            }
            
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error during import: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}

