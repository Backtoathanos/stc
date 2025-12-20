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

class FixMissingData extends Command
{
    protected $signature = 'import:fix-missing {--dry-run : Show what would be imported without actually importing}';
    protected $description = 'Identify and import missing employees and rates from userdata.txt';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Analyzing missing data...');
        
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
        
        // Get existing data from database
        $existingEmployeeIds = Employee::pluck('id')->toArray();
        $existingRateEmployeeIds = Rate::pluck('employee_id')->toArray();
        
        $this->info('Found ' . count($existingEmployeeIds) . ' employees in database');
        $this->info('Found ' . count($existingRateEmployeeIds) . ' rates in database');
        
        // Find missing employees
        $missingEmployees = [];
        $missingRates = [];
        $employeesWithErrors = [];
        
        foreach ($data as $record) {
            $employeeId = $record['id'] ?? null;
            
            if (!$employeeId) {
                continue;
            }
            
            // Check if employee exists
            if (!in_array($employeeId, $existingEmployeeIds)) {
                $missingEmployees[] = $record;
            } else {
                // Employee exists, check if rate exists
                if (isset($record['rate']) && !in_array($employeeId, $existingRateEmployeeIds)) {
                    $missingRates[] = [
                        'employee_id' => $employeeId,
                        'rate_data' => $record['rate']
                    ];
                }
            }
        }
        
        $this->info('');
        $this->info('=== ANALYSIS RESULTS ===');
        $this->info('Missing Employees: ' . count($missingEmployees));
        $this->info('Missing Rates: ' . count($missingRates));
        
        if (count($missingEmployees) > 0) {
            $this->warn('Missing Employee IDs: ' . implode(', ', array_column($missingEmployees, 'id')));
        }
        
        if (count($missingRates) > 0) {
            $this->warn('Missing Rate Employee IDs: ' . implode(', ', array_column($missingRates, 'employee_id')));
        }
        
        if ($dryRun) {
            $this->info('');
            $this->info('DRY RUN MODE - No data will be imported');
            return 0;
        }
        
        if (count($missingEmployees) === 0 && count($missingRates) === 0) {
            $this->info('No missing data found!');
            return 0;
        }
        
        $this->info('');
        if (!$this->confirm('Do you want to import the missing data?', true)) {
            $this->info('Import cancelled.');
            return 0;
        }
        
        DB::beginTransaction();
        
        try {
            // Import missing employees
            if (count($missingEmployees) > 0) {
                $this->info('Importing ' . count($missingEmployees) . ' missing employees...');
                $progressBar = $this->output->createProgressBar(count($missingEmployees));
                $progressBar->start();
                
                foreach ($missingEmployees as $record) {
                    try {
                        $siteId = $record['SiteDetails']['id'] ?? 1;
                        $deptId = $record['DepartmentDetails']['id'] ?? 1;
                        $desigId = $record['DesignationDetails']['id'] ?? 1;
                        $gangId = $record['GangDetails']['id'] ?? 1;
                        
                        // Ensure master data exists
                        if (!Site::find($siteId)) {
                            if (isset($record['SiteDetails'])) {
                                Site::create([
                                    'id' => $siteId,
                                    'name' => $record['SiteDetails']['name']
                                ]);
                            }
                        }
                        
                        if (!Department::find($deptId)) {
                            if (isset($record['DepartmentDetails'])) {
                                Department::create([
                                    'id' => $deptId,
                                    'name' => $record['DepartmentDetails']['name']
                                ]);
                            }
                        }
                        
                        if (!Designation::find($desigId)) {
                            if (isset($record['DesignationDetails'])) {
                                Designation::create([
                                    'id' => $desigId,
                                    'name' => $record['DesignationDetails']['name']
                                ]);
                            }
                        }
                        
                        if (!Gang::find($gangId)) {
                            if (isset($record['GangDetails'])) {
                                Gang::create([
                                    'id' => $gangId,
                                    'name' => $record['GangDetails']['name']
                                ]);
                            }
                        }
                        
                        $employeeData = [
                            'id' => $record['id'],
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
                        
                        $employee = Employee::create($employeeData);
                        
                        // Import rate if available
                        if (isset($record['rate']) && $employee && $employee->id) {
                            $rateId = $record['rate']['id'] ?? null;
                            $rateData = [
                                'id' => $rateId,
                                'employee_id' => $employee->id,
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
                            ];
                            
                            if ($rateId) {
                                Rate::updateOrCreate(
                                    ['id' => $rateId],
                                    $rateData
                                );
                            } else {
                                Rate::create($rateData);
                            }
                        }
                    } catch (\Exception $e) {
                        $employeesWithErrors[] = [
                            'id' => $record['id'],
                            'error' => $e->getMessage()
                        ];
                    }
                    
                    $progressBar->advance();
                }
                
                $progressBar->finish();
                $this->line('');
            }
            
            // Import missing rates for existing employees
            if (count($missingRates) > 0) {
                $this->info('Importing ' . count($missingRates) . ' missing rates...');
                $progressBar = $this->output->createProgressBar(count($missingRates));
                $progressBar->start();
                
                foreach ($missingRates as $rateInfo) {
                    try {
                        $employee = Employee::find($rateInfo['employee_id']);
                        if ($employee) {
                            $rateId = $rateInfo['rate_data']['id'] ?? null;
                            $rateData = [
                                'id' => $rateId,
                                'employee_id' => $employee->id,
                                'ctc' => $rateInfo['rate_data']['ctc'] ?? 0,
                                'basic' => $rateInfo['rate_data']['basic'] ?? 0,
                                'da' => $rateInfo['rate_data']['da'] ?? 0,
                                'arate' => $rateInfo['rate_data']['arate'] ?? 0,
                                'otrate' => $rateInfo['rate_data']['otrate'] ?? 0,
                                'hra' => $rateInfo['rate_data']['hra'] ?? 0,
                                'madical' => $rateInfo['rate_data']['madical'] ?? 0,
                                'ExgratiaRetention' => $rateInfo['rate_data']['ExgratiaRetention'] ?? 0,
                                'LTARetention' => $rateInfo['rate_data']['LTARetention'] ?? 0,
                                'LTA' => $rateInfo['rate_data']['LTA'] ?? 0,
                                'CA' => $rateInfo['rate_data']['CA'] ?? 0,
                                'Fooding' => $rateInfo['rate_data']['Fooding'] ?? 0,
                                'Misc' => $rateInfo['rate_data']['Misc'] ?? 0,
                                'CEA' => $rateInfo['rate_data']['CEA'] ?? 0,
                                'WashingAllowance' => $rateInfo['rate_data']['WashingAllowance'] ?? 0,
                                'ProfessionalPursuits' => $rateInfo['rate_data']['ProfessionalPursuits'] ?? 0,
                                'SpecialAllowance' => $rateInfo['rate_data']['SpecialAllowance'] ?? 0,
                                'IncomeTax' => $rateInfo['rate_data']['IncomeTax'] ?? 0,
                                'personalpay' => $rateInfo['rate_data']['personalpay'] ?? 0,
                                'petrol' => $rateInfo['rate_data']['petrol'] ?? 0,
                                'mobile' => $rateInfo['rate_data']['mobile'] ?? 0,
                                'incentive' => $rateInfo['rate_data']['incentive'] ?? 0,
                                'fixedamt' => $rateInfo['rate_data']['fixedamt'] ?? 0,
                                'netamt' => $rateInfo['rate_data']['netamt'] ?? 0,
                            ];
                            
                            if ($rateId) {
                                Rate::updateOrCreate(
                                    ['id' => $rateId],
                                    $rateData
                                );
                            } else {
                                Rate::create($rateData);
                            }
                        }
                    } catch (\Exception $e) {
                        $this->warn("Error creating rate for employee {$rateInfo['employee_id']}: " . $e->getMessage());
                    }
                    
                    $progressBar->advance();
                }
                
                $progressBar->finish();
                $this->line('');
            }
            
            DB::commit();
            
            $this->info('');
            $this->info('=== IMPORT COMPLETED ===');
            $this->info('Total Employees in DB: ' . Employee::count());
            $this->info('Total Rates in DB: ' . Rate::count());
            
            if (count($employeesWithErrors) > 0) {
                $this->warn('Employees with errors: ' . count($employeesWithErrors));
                foreach ($employeesWithErrors as $error) {
                    $this->warn("  Employee ID {$error['id']}: {$error['error']}");
                }
            }
            
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error importing data: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}

