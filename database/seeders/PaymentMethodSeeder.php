<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment_methods = [
            [
                'name' => 'Cash On Delivery',
                'status' => TRUE,
                'account_number' => null,
            ],
            [
                'name' => 'HelaPay',
                'status' => TRUE,
                'account_number' => null,
            ],
            [
                'name' => 'Online Transfer',
                'status' => TRUE,
                'account_number' => null,
            ],
            [
                'name' => 'Paypal',
                'status' => TRUE,
                'account_number' => null,
            ],
        ];

        PaymentMethod::insert($payment_methods);
    }
}
