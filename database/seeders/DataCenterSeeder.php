<?php

namespace Database\Seeders;

use App\Models\DataCenter;
use Illuminate\Database\Seeder;

class DataCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['center' => 'Velia'],
            ['center' => 'EasySpace'],
            ['center' => 'Hetzner'],
            ['center' => 'Server Deals'],
            ['center' => 'Vultr'],
            ['center' => 'Taiwan'],
            ['center' => 'Leaseweb'],
            ['center' => 'Hostnnoc'],
            ['center' => 'GTHost'],
        ];
        DataCenter::insert($data);
    }
}
