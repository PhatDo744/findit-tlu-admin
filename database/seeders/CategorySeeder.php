<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name'=>'Điện tử - Công nghệ','description'=>'Điện thoại, laptop...','sort_order'=>1,'is_active'=>true],
            // ...các mục khác...
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
