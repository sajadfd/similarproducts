<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();
        // Insert products in task
        $similarProducts = [
            ['name' => 'red shirt', 'frequency' => 10],
            ['name' => 'blue shirt', 'frequency' => 5],
            ['name' => 'yellow shirt', 'frequency' => 3],
            ['name' => 'green shorts', 'frequency' => 2],
            ['name' => 'green pants', 'frequency' => 7],
            ['name' => 'gray jeans', 'frequency' => 4],
            ['name' => 'white t-shirt', 'frequency' => 3]
        ];
        foreach ($similarProducts as $product) {
            DB::table('products')->insert($product);
        }
        // Insert 100 random products
        for ($i = 0; $i < 100; $i++) {
            $name = $faker->colorName . ' ' . $faker->word.' '.$faker->randomElement(['shirt', 'pants', 'shorts', 'jacket']);
            $frequency = $faker->numberBetween(0, 10);
            DB::table('products')->insert([
                'name' => $name,
                'frequency' => $frequency,
            ]);
        }
    }
}
