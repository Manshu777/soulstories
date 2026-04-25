<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Romance',
            'Horror',
            'Dark',
            'College',
            'Thriller',
            'Short Stories',
            'Motivation',
            'Life',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'status' => 'active',
                ]
            );
        }
    }
}
