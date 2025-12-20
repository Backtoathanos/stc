<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Employee;
use App\Rate;
use Illuminate\Support\Facades\DB;

class FixRateIds extends Command
{
    protected $signature = 'import:fix-rate-ids {--dry-run : Show what would be updated without actually updating}';
    protected $description = 'Fix rate IDs to match the original IDs from userdata.txt';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Fixing rate IDs to match userdata.txt...');
        
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
        
        // Build a map of employee_id => rate_id from userdata.txt
        $expectedRateIds = [];
        foreach ($data as $record) {
            if (isset($record['id']) && isset($record['rate']['id'])) {
                $expectedRateIds[$record['id']] = $record['rate']['id'];
            }
        }
        
        $this->info('Found ' . count($expectedRateIds) . ' rate mappings in userdata.txt');
        
        // Get all rates from database
        $rates = Rate::all();
        $this->info('Found ' . $rates->count() . ' rates in database');
        
        $ratesToUpdate = [];
        $ratesToDelete = [];
        $ratesToCreate = [];
        
        foreach ($rates as $rate) {
            $employeeId = $rate->employee_id;
            $currentRateId = $rate->id;
            $expectedRateId = $expectedRateIds[$employeeId] ?? null;
            
            if ($expectedRateId) {
                if ($currentRateId != $expectedRateId) {
                    // Check if expected rate ID already exists
                    $existingRate = Rate::find($expectedRateId);
                    if ($existingRate && $existingRate->employee_id != $employeeId) {
                        // Conflict: expected ID exists for different employee
                        $this->warn("Rate ID {$expectedRateId} already exists for employee {$existingRate->employee_id}, current rate ID is {$currentRateId} for employee {$employeeId}");
                        $ratesToDelete[] = $currentRateId;
                        $ratesToCreate[] = [
                            'employee_id' => $employeeId,
                            'rate_id' => $expectedRateId,
                            'data' => $rate->toArray()
                        ];
                    } else {
                        $ratesToUpdate[] = [
                            'current_id' => $currentRateId,
                            'new_id' => $expectedRateId,
                            'employee_id' => $employeeId,
                            'data' => $rate->toArray()
                        ];
                    }
                }
            }
        }
        
        // Find rates that should exist but don't (only for employees that exist in DB)
        $existingEmployeeIds = Employee::pluck('id')->toArray();
        foreach ($expectedRateIds as $employeeId => $expectedRateId) {
            // Only create rate if employee exists in database
            if (!in_array($employeeId, $existingEmployeeIds)) {
                continue;
            }
            
            $rate = Rate::where('employee_id', $employeeId)->first();
            if (!$rate) {
                // Find the record in userdata.txt
                foreach ($data as $record) {
                    if ($record['id'] == $employeeId && isset($record['rate'])) {
                        $ratesToCreate[] = [
                            'employee_id' => $employeeId,
                            'rate_id' => $expectedRateId,
                            'data' => $record['rate']
                        ];
                        break;
                    }
                }
            }
        }
        
        $this->info('');
        $this->info('=== ANALYSIS RESULTS ===');
        $this->info('Rates to update (ID change): ' . count($ratesToUpdate));
        $this->info('Rates to create (missing): ' . count($ratesToCreate));
        $this->info('Rates to delete (conflicts): ' . count($ratesToDelete));
        
        if ($dryRun) {
            $this->info('');
            $this->info('DRY RUN MODE - No data will be updated');
            if (count($ratesToUpdate) > 0) {
                $this->info('');
                $this->info('Sample rates to update:');
                foreach (array_slice($ratesToUpdate, 0, 5) as $update) {
                    $this->info("  Employee {$update['employee_id']}: Rate ID {$update['current_id']} -> {$update['new_id']}");
                }
            }
            return 0;
        }
        
        if (count($ratesToUpdate) === 0 && count($ratesToCreate) === 0 && count($ratesToDelete) === 0) {
            $this->info('No rate ID fixes needed!');
            return 0;
        }
        
        $this->info('');
        if (!$this->confirm('Do you want to fix the rate IDs?', true)) {
            $this->info('Fix cancelled.');
            return 0;
        }
        
        DB::beginTransaction();
        
        try {
            // Delete conflicting rates
            if (count($ratesToDelete) > 0) {
                $this->info('Deleting ' . count($ratesToDelete) . ' conflicting rates...');
                Rate::whereIn('id', $ratesToDelete)->delete();
            }
            
            // Update rate IDs
            if (count($ratesToUpdate) > 0) {
                $this->info('Updating ' . count($ratesToUpdate) . ' rate IDs...');
                $progressBar = $this->output->createProgressBar(count($ratesToUpdate));
                $progressBar->start();
                
                $updateErrors = [];
                $updatedCount = 0;
                
                foreach ($ratesToUpdate as $update) {
                    try {
                        // Verify employee still exists
                        $employee = Employee::find($update['employee_id']);
                        if (!$employee) {
                            $updateErrors[] = [
                                'employee_id' => $update['employee_id'],
                                'rate_id' => $update['new_id'],
                                'reason' => 'Employee does not exist'
                            ];
                            $progressBar->advance();
                            continue;
                        }
                        
                        // Check if new rate ID already exists
                        $existingRate = Rate::find($update['new_id']);
                        if ($existingRate && $existingRate->employee_id != $update['employee_id']) {
                            $updateErrors[] = [
                                'employee_id' => $update['employee_id'],
                                'rate_id' => $update['new_id'],
                                'reason' => 'Rate ID already exists for different employee'
                            ];
                            $progressBar->advance();
                            continue;
                        }
                        
                        // Delete old rate
                        Rate::where('id', $update['current_id'])->delete();
                        
                        // Create new rate with correct ID
                        $rateData = $update['data'];
                        $rateData['id'] = $update['new_id'];
                        $rateData['employee_id'] = $update['employee_id'];
                        unset($rateData['created_at'], $rateData['updated_at']);
                        
                        Rate::create($rateData);
                        $updatedCount++;
                        
                    } catch (\Exception $e) {
                        $updateErrors[] = [
                            'employee_id' => $update['employee_id'],
                            'rate_id' => $update['new_id'],
                            'reason' => $e->getMessage()
                        ];
                    }
                    
                    $progressBar->advance();
                }
                
                $progressBar->finish();
                $this->line('');
                
                if (count($updateErrors) > 0) {
                    $this->warn('Errors updating ' . count($updateErrors) . ' rates:');
                    foreach (array_slice($updateErrors, 0, 10) as $error) {
                        $this->warn("  Employee {$error['employee_id']}, Rate {$error['rate_id']}: {$error['reason']}");
                    }
                    if (count($updateErrors) > 10) {
                        $this->warn("  ... and " . (count($updateErrors) - 10) . " more");
                    }
                }
                
                $this->info("Successfully updated {$updatedCount} rate IDs");
            }
            
            // Create missing rates
            if (count($ratesToCreate) > 0) {
                $this->info('Creating ' . count($ratesToCreate) . ' missing rates...');
                $progressBar = $this->output->createProgressBar(count($ratesToCreate));
                $progressBar->start();
                
                $skippedRates = [];
                $createdCount = 0;
                
                foreach ($ratesToCreate as $create) {
                    try {
                        // Check if employee exists
                        $employee = Employee::find($create['employee_id']);
                        if (!$employee) {
                            $skippedRates[] = [
                                'employee_id' => $create['employee_id'],
                                'rate_id' => $create['rate_id'],
                                'reason' => 'Employee does not exist'
                            ];
                            $progressBar->advance();
                            continue;
                        }
                        
                        // Check if rate ID already exists
                        $existingRate = Rate::find($create['rate_id']);
                        if ($existingRate) {
                            if ($existingRate->employee_id == $create['employee_id']) {
                                // Rate already exists for this employee, skip
                                $progressBar->advance();
                                continue;
                            } else {
                                $skippedRates[] = [
                                    'employee_id' => $create['employee_id'],
                                    'rate_id' => $create['rate_id'],
                                    'reason' => 'Rate ID already exists for different employee'
                                ];
                                $progressBar->advance();
                                continue;
                            }
                        }
                        
                        $rateData = [
                            'id' => $create['rate_id'],
                            'employee_id' => $create['employee_id'],
                            'ctc' => $create['data']['ctc'] ?? 0,
                            'basic' => $create['data']['basic'] ?? 0,
                            'da' => $create['data']['da'] ?? 0,
                            'arate' => $create['data']['arate'] ?? 0,
                            'otrate' => $create['data']['otrate'] ?? 0,
                            'hra' => $create['data']['hra'] ?? 0,
                            'madical' => $create['data']['madical'] ?? 0,
                            'ExgratiaRetention' => $create['data']['ExgratiaRetention'] ?? 0,
                            'LTARetention' => $create['data']['LTARetention'] ?? 0,
                            'LTA' => $create['data']['LTA'] ?? 0,
                            'CA' => $create['data']['CA'] ?? 0,
                            'Fooding' => $create['data']['Fooding'] ?? 0,
                            'Misc' => $create['data']['Misc'] ?? 0,
                            'CEA' => $create['data']['CEA'] ?? 0,
                            'WashingAllowance' => $create['data']['WashingAllowance'] ?? 0,
                            'ProfessionalPursuits' => $create['data']['ProfessionalPursuits'] ?? 0,
                            'SpecialAllowance' => $create['data']['SpecialAllowance'] ?? 0,
                            'IncomeTax' => $create['data']['IncomeTax'] ?? 0,
                            'personalpay' => $create['data']['personalpay'] ?? 0,
                            'petrol' => $create['data']['petrol'] ?? 0,
                            'mobile' => $create['data']['mobile'] ?? 0,
                            'incentive' => $create['data']['incentive'] ?? 0,
                            'fixedamt' => $create['data']['fixedamt'] ?? 0,
                            'netamt' => $create['data']['netamt'] ?? 0,
                        ];
                        
                        Rate::create($rateData);
                        $createdCount++;
                        
                    } catch (\Exception $e) {
                        $skippedRates[] = [
                            'employee_id' => $create['employee_id'],
                            'rate_id' => $create['rate_id'],
                            'reason' => $e->getMessage()
                        ];
                    }
                    
                    $progressBar->advance();
                }
                
                $progressBar->finish();
                $this->line('');
                
                if (count($skippedRates) > 0) {
                    $this->warn('Skipped ' . count($skippedRates) . ' rates:');
                    foreach (array_slice($skippedRates, 0, 10) as $skipped) {
                        $this->warn("  Employee {$skipped['employee_id']}, Rate {$skipped['rate_id']}: {$skipped['reason']}");
                    }
                    if (count($skippedRates) > 10) {
                        $this->warn("  ... and " . (count($skippedRates) - 10) . " more");
                    }
                }
                
                $this->info("Successfully created {$createdCount} rates");
            }
            
            DB::commit();
            
            $this->info('');
            $this->info('=== FIX COMPLETED ===');
            $this->info('Total Rates in DB: ' . Rate::count());
            
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error fixing rate IDs: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}

