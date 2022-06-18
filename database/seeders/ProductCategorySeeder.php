<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product_category = [
            [
                'name' => 'For Living',
            ],
            [
                'name' => 'For Business',
            ],
            [
                'name' => 'For Planting',
            ],
        ];

        foreach ($product_category as $key => $value) {
            ProductCategory::create($value);
        }
    }
}
