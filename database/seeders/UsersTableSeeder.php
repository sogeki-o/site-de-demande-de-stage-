<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Test users
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Test',
            'email' => 'admin@uca.ma',
            'password' => Hash::make('admin123'),
            'telephone' => '0600000000',
            'role' => 'admin',
            'actif' => true,
        ]);

        User::create([
            'nom' => 'RH',
            'prenom' => 'Test',
            'email' => 'rh@uca.ma',
            'password' => Hash::make('rh123'),
            'telephone' => '0600000001',
            'role' => 'rh',
            'actif' => true,
        ]);

        User::create([
            'nom' => 'Service',
            'prenom' => 'Info',
            'email' => 'service.info@uca.ma',
            'password' => Hash::make('service123'),
            'telephone' => '0600000002',
            'role' => 'service',
            'actif' => true,
        ]);

        User::create([
            'nom' => 'Demandeur',
            'prenom' => 'Test',
            'email' => 'demandeur@uca.ma',
            'password' => Hash::make('demandeur123'),
            'telephone' => '0600000003',
            'role' => 'demandeur',
            'actif' => true,
        ]);
    }
}
