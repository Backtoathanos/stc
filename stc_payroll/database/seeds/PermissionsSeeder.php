<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // Master Section
            ['section' => 'Master', 'page' => 'Sites', 'operation' => 'view', 'slug' => 'master.sites.view'],
            ['section' => 'Master', 'page' => 'Sites', 'operation' => 'edit', 'slug' => 'master.sites.edit'],
            ['section' => 'Master', 'page' => 'Sites', 'operation' => 'delete', 'slug' => 'master.sites.delete'],
            
            ['section' => 'Master', 'page' => 'Departments', 'operation' => 'view', 'slug' => 'master.departments.view'],
            ['section' => 'Master', 'page' => 'Departments', 'operation' => 'edit', 'slug' => 'master.departments.edit'],
            ['section' => 'Master', 'page' => 'Departments', 'operation' => 'delete', 'slug' => 'master.departments.delete'],
            
            ['section' => 'Master', 'page' => 'Designations', 'operation' => 'view', 'slug' => 'master.designations.view'],
            ['section' => 'Master', 'page' => 'Designations', 'operation' => 'edit', 'slug' => 'master.designations.edit'],
            ['section' => 'Master', 'page' => 'Designations', 'operation' => 'delete', 'slug' => 'master.designations.delete'],
            
            ['section' => 'Master', 'page' => 'Gangs', 'operation' => 'view', 'slug' => 'master.gangs.view'],
            ['section' => 'Master', 'page' => 'Gangs', 'operation' => 'edit', 'slug' => 'master.gangs.edit'],
            ['section' => 'Master', 'page' => 'Gangs', 'operation' => 'delete', 'slug' => 'master.gangs.delete'],
            
            ['section' => 'Master', 'page' => 'Employees', 'operation' => 'view', 'slug' => 'master.employees.view'],
            ['section' => 'Master', 'page' => 'Employees', 'operation' => 'edit', 'slug' => 'master.employees.edit'],
            ['section' => 'Master', 'page' => 'Employees', 'operation' => 'delete', 'slug' => 'master.employees.delete'],
            
            // Transaction Section
            ['section' => 'Transaction', 'page' => 'Attendance', 'operation' => 'view', 'slug' => 'transaction.attendance.view'],
            ['section' => 'Transaction', 'page' => 'Attendance', 'operation' => 'edit', 'slug' => 'transaction.attendance.edit'],
            ['section' => 'Transaction', 'page' => 'Attendance', 'operation' => 'delete', 'slug' => 'transaction.attendance.delete'],
            
            // Reports Section
            ['section' => 'Reports', 'page' => 'Payroll', 'operation' => 'view', 'slug' => 'reports.payroll.view'],
            ['section' => 'Reports', 'page' => 'Payroll', 'operation' => 'edit', 'slug' => 'reports.payroll.edit'],
            ['section' => 'Reports', 'page' => 'Payroll', 'operation' => 'delete', 'slug' => 'reports.payroll.delete'],
            
            // Admin Section
            ['section' => 'Admin', 'page' => 'Users', 'operation' => 'view', 'slug' => 'admin.users.view'],
            ['section' => 'Admin', 'page' => 'Users', 'operation' => 'edit', 'slug' => 'admin.users.edit'],
            ['section' => 'Admin', 'page' => 'Users', 'operation' => 'delete', 'slug' => 'admin.users.delete'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully!');
    }
}

