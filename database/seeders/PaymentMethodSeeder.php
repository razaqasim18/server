<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        PaymentMethod::insert([
            [
                'title' => "Other",
                'slug' => "slug",
                'publickey' => "",
                'secretkey' => "",
            ], [
                'title' => "Stripe",
                'slug' => "stripe",
                'publickey' => "pk_test_CjYRexvAYguIvW2LhGNzolAV00h7pGdQMb",
                'secretkey' => "sk_test_S6LiIrGRIAkj72BrtNa3VkIV00BAOpdCp3",
            ],
            //  [
            //     'title' => "Paypal",
            //     'slug' => "paypal",
            //     'publickey' => "",
            //     'secretkey' => "",
            // ],
        ]);
    }
}
