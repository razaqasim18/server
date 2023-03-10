<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;
class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            [
                'package' => '500 CC',
                'category_id' => '1',
                'price' => '49',
            ],
            [
                'package' => '1000 CC',
                'category_id' => '1',
                'price' => '79',
            ],
        ];
        Package::insert($data);
    }
}
