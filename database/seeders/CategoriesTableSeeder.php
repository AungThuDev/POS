<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
            ['id' => 1, 'name' => 'Test1'],
            ['id' => 2, 'name' => 'Test2'],
            ['id' => 3, 'name' => 'Test3'],
        ];

        Category::insert($records);
    }
}
