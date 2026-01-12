<?php

namespace Database\Seeders;

use App\Models\Version;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Version::factory()->createMany([
            ['title' => '8.61', 'release_date' => '2021-09-14'],
            ['title' => '8.60', 'release_date' => '2021-08-10'],
            ['title' => '8.59', 'release_date' => '2021-07-08'],
            ['title' => '8.58', 'release_date' => '2021-05-24'],
            ['title' => '8.57', 'release_date' => '2021-04-12'],
        ]);
    }
}
