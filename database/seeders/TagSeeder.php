<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\TagEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (TagEnum::toArray() as $tagTitle) {
            Tag::query()
                ->firstOrCreate([
                    'title' => $tagTitle
                 ]);
        }
    }
}
