<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a user
        $user = User::factory()->create([
            'first_name' => 'Fakhri',
            'last_name' => 'Chargui',
            'email' => 'fakhri.chargui37@gmail.com',
            'password' => bcrypt('20855016'),
            'phone' => '123456789',
            'sratus' => 'valide',
            'role'=>'admin'
        ]);

        // Attach role to user
      //  $user->roles()->attach($adminRole->id);
    }
}
