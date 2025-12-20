<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class RootUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if root user already exists
        $rootUser = User::where('email', 'root@stcassociate.com')->first();
        
        if (!$rootUser) {
            User::create([
                'name' => 'Root Administrator',
                'email' => 'root@stcassociate.com',
                'password' => Hash::make('Root@STC2024!'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Root user created successfully!');
            $this->command->info('Email: root@stcassociate.com');
            $this->command->info('Password: Root@STC2024!');
        } else {
            // Update existing root user to ensure status is active
            $rootUser->status = 'active';
            $rootUser->save();
            $this->command->info('Root user already exists!');
            $this->command->info('Email: root@stcassociate.com');
            $this->command->info('Status updated to active.');
        }
    }
}

