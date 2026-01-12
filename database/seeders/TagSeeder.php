<?php

namespace Database\Seeders;

use App\Enums\TagEnum;
use App\Models\Tag;
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
                    'title' => $tagTitle,
                ]);
        }
    }
}
