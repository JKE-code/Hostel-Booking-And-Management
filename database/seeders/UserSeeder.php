<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────────
        User::create([
            'name'      => 'System Administrator',
            'email'     => 'admin@hitam.org',
            'password'  => Hash::make('admin@hitam123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // ── Chief Warden ─────────────────────────────────────────
        User::create([
            'name'      => 'Prof. V. Ramanjaneyulu',
            'email'     => 'chiefwarden@hitam.org',
            'password'  => Hash::make('warden@hitam123'),
            'role'      => 'warden',
            'is_active' => true,
        ]);

        // ── Resident Warden – Boys ───────────────────────────────
        User::create([
            'name'      => 'Mr. K. Sreenivasulu',
            'email'     => 'warden.boys@hitam.org',
            'password'  => Hash::make('warden@hitam123'),
            'role'      => 'warden',
            'is_active' => true,
        ]);

        // ── Resident Warden – Girls ──────────────────────────────
        User::create([
            'name'      => 'Ms. P. Lakshmi',
            'email'     => 'warden.girls@hitam.org',
            'password'  => Hash::make('warden@hitam123'),
            'role'      => 'warden',
            'is_active' => true,
        ]);

        // ── Security / Main Gate Desk ───────────────────────────
        User::create([
            'name'      => 'HITAM Main Gate Security Desk',
            'email'     => 'security@hitam.org',
            'password'  => Hash::make('security@hitam123'),
            'role'      => 'security',
            'is_active' => true,
        ]);

        // ── Demo Student Users ───────────────────────────────────
        $demoStudents = [
            ['name' => 'Rahul Sharma',     'email' => 'rahul.sharma@student.hitam.org'],
            ['name' => 'Kavya Reddy',      'email' => 'kavya.reddy@student.hitam.org'],
            ['name' => 'Sai Teja',         'email' => 'sai.teja@student.hitam.org'],
            ['name' => 'Priya Nair',       'email' => 'priya.nair@student.hitam.org'],
            ['name' => 'V. Chaitanya',     'email' => 'v.chaitanya@student.hitam.org'],
        ];

        foreach ($demoStudents as $s) {
            User::create([
                'name'      => $s['name'],
                'email'     => $s['email'],
                'password'  => Hash::make('student@hitam123'),
                'role'      => 'student',
                'is_active' => true,
            ]);
        }
    }
}
