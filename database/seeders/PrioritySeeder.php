<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Priority;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['priority' => 'Normal'],
            ['priority' => 'High'],
            ['priority' => 'Urgent']
        ];
        Priority::insert($data);
    }
}