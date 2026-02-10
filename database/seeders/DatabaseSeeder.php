<?php

namespace Database\Seeders;

use App\Models\CreditPackage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@tubekitai.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
            'credits' => 1000,
            'locale' => 'tr',
        ]);

        // Create demo user
        User::create([
            'name' => 'Demo Kullanıcı',
            'email' => 'demo@tubekitai.com',
            'password' => Hash::make('demo123'),
            'email_verified_at' => now(),
            'is_admin' => false,
            'credits' => 50,
            'locale' => 'tr',
        ]);

        // Create credit packages
        $packages = [
            [
                'slug' => 'starter',
                'name' => 'Starter',
                'description' => 'Perfect for getting started',
                'credits' => 15,
                'price' => 14900,
                'currency' => 'TRY',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 1,
                'features' => [
                    'Channel Analysis (15x)',
                    'Comment Analysis (5x)',
                    'Cover Analysis (30x)',
                ],
            ],
            [
                'slug' => 'creator',
                'name' => 'Creator',
                'description' => 'Most popular for creators',
                'credits' => 35,
                'price' => 29900,
                'currency' => 'TRY',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
                'features' => [
                    'Channel Analysis (35x)',
                    'Comment Analysis (11x)',
                    'Cover Analysis (70x)',
                    'Video Ideas (35x)',
                ],
            ],
            [
                'slug' => 'pro',
                'name' => 'Pro',
                'description' => 'For serious content creators',
                'credits' => 70,
                'price' => 49900,
                'currency' => 'TRY',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 3,
                'features' => [
                    'Channel Analysis (70x)',
                    'Comment Analysis (23x)',
                    'Cover Analysis (140x)',
                    'TransFlow Translations (11x)',
                    'All Features',
                ],
            ],
            [
                'slug' => 'agency',
                'name' => 'Agency',
                'description' => 'For agencies and teams',
                'credits' => 150,
                'price' => 99900,
                'currency' => 'TRY',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 4,
                'features' => [
                    'Channel Analysis (150x)',
                    'Comment Analysis (50x)',
                    'Cover Analysis (300x)',
                    'TransFlow Translations (25x)',
                    'All Features',
                    'Priority Support',
                ],
            ],
        ];

        foreach ($packages as $package) {
            CreditPackage::create($package);
        }
    }
}
