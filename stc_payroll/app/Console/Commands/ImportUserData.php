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

class ImportUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:userdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import user data from userdata.txt file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Starting import process...');
        
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
        
        $this->info('Found ' . count($data) . ' records to import');
        
        DB::beginTransaction();
        
        try {
            $sitesMap = [];
            $departmentsMap = [];
            $designationsMap = [];
            $gangsMap = [];
            
            $this->info('Collecting master data...');
            
            // First pass: Collect all unique master data
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
            
            $this->info('Importing master data...');
            
            // Create all sites first
            foreach ($sitesMap as $siteData) {
                $site = Site::find($siteData['id']);
                if (!$site) {
                    Site::create([
                        'id' => $siteData['id'],
                        'name' => $siteData['name']
                    ]);
                } else {
                    $site->update(['name' => $siteData['name']]);
                }
            }
            
            // Create all departments
            foreach ($departmentsMap as $deptData) {
                $dept = Department::find($deptData['id']);
                if (!$dept) {
                    Department::create([
                        'id' => $deptData['id'],
                        'name' => $deptData['name']
                    ]);
                } else {
                    $dept->update(['name' => $deptData['name']]);
                }
            }
            
            // Create all designations
            foreach ($designationsMap as $desigData) {
                $desig = Designation::find($desigData['id']);
                if (!$desig) {
                    Designation::create([
                        'id' => $desigData['id'],
                        'name' => $desigData['name']
                    ]);
                } else {
                    $desig->update(['name' => $desigData['name']]);
                }
            }
            
            // Create all gangs
            foreach ($gangsMap as $gangData) {
                $gang = Gang::find($gangData['id']);
                if (!$gang) {
                    Gang::create([
                        'id' => $gangData['id'],
                        'name' => $gangData['name']
                    ]);
                } else {
                    $gang->update(['name' => $gangData['name']]);
                }
            }
            
            $this->info('Master data imported: ' . count($sitesMap) . ' sites, ' . count($departmentsMap) . ' departments, ' . count($designationsMap) . ' designations, ' . count($gangsMap) . ' gangs');
            
            // Verify all master data was created
            $createdSites = Site::count();
            $createdDepts = Department::count();
            $createdDesigs = Designation::count();
            $createdGangs = Gang::count();
            $this->info("Verified in DB: {$createdSites} sites, {$createdDepts} departments, {$createdDesigs} designations, {$createdGangs} gangs");
            
            // Check if designation 92 exists
            if (Designation::find(92)) {
                $this->info('Designation 92 exists in database');
            } else {
                $this->warn('Designation 92 does NOT exist in database!');
                $this->info('Available designation IDs: ' . Designation::pluck('id')->implode(', '));
            }
            
            $this->info('Importing employees...');
            $progressBar = $this->output->createProgressBar(count($data));
            $progressBar->start();
            
            foreach ($data as $record) {
                $siteId = $record['SiteDetails']['id'] ?? null;
                $deptId = $record['DepartmentDetails']['id'] ?? null;
                $desigId = $record['DesignationDetails']['id'] ?? null;
                $gangId = $record['GangDetails']['id'] ?? null;
                
                // Validate foreign keys exist
                if ($siteId && !Site::find($siteId)) {
                    $this->warn("Site ID {$siteId} not found for employee {$record['id']}, skipping...");
                    $progressBar->advance();
                    continue;
                }
                if ($deptId && !Department::find($deptId)) {
                    $this->warn("Department ID {$deptId} not found for employee {$record['id']}, skipping...");
                    $progressBar->advance();
                    continue;
                }
                if ($desigId && !Designation::find($desigId)) {
                    $this->warn("Designation ID {$desigId} not found for employee {$record['id']}, skipping...");
                    $progressBar->advance();
                    continue;
                }
                if ($gangId && !Gang::find($gangId)) {
                    $this->warn("Gang ID {$gangId} not found for employee {$record['id']}, skipping...");
                    $progressBar->advance();
                    continue;
                }
                
                $employeeData = [
                    'id' => $record['id'],
                    'site_id' => $siteId ?? 1,
                    'department_id' => $deptId ?? 1,
                    'designation_id' => $desigId ?? 1,
                    'gang_id' => $gangId ?? 1,
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
                
                try {
                    $employee = Employee::updateOrCreate(
                        ['id' => $record['id']],
                        $employeeData
                    );
                    
                    if (!$employee || !$employee->id) {
                        $this->warn("Failed to create employee {$record['id']}, skipping...");
                        $progressBar->advance();
                        continue;
                    }
                } catch (\Exception $e) {
                    $this->warn("Error creating employee {$record['id']}: " . $e->getMessage() . ", skipping...");
                    $progressBar->advance();
                    continue;
                }
                
                // Import Rate
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
                        Rate::updateOrCreate(
                            ['employee_id' => $employee->id],
                            $rateData
                        );
                    }
                }
                
                $progressBar->advance();
            }
            
            $progressBar->finish();
            $this->line('');
            
            DB::commit();
            $this->info('Import completed successfully!');
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error importing data: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
