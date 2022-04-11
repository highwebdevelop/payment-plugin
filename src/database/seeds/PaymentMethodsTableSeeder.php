<?php

namespace Payment\System\Database\Seeds;
use Payment\System\App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payments = array(
            [
                'operator' => 'capitalist',
                'method' => 'bank_card',
                'logo' => 'storage/payments/credit-card.svg',
                'is_recurrent' => true
            ],
            [
                'operator' => 'paypal',
                'method' => 'paypal',
                'logo' => 'storage/payments/paypal.svg',
                'is_recurrent' => true
            ],
            [
                'operator' => 'yandex',
                'method' => 'qiwi',
                'logo' => 'storage/payments/qiwi.svg',
            ],
            [
                'operator' => 'yandex',
                'method' => 'bank_card',
                'logo' => 'storage/payments/credit-card.svg',
                'is_active' => false
            ],
            [
                'operator' => 'yandex',
                'method' => 'yandex_money',
                'logo' => 'storage/payments/yandex-money.svg',
            ],
            [
                'operator' => 'yandex',
                'method' => 'webmoney',
                'logo' => 'storage/payments/webmoney.svg'
            ],
            [
                'operator' => 'yandex',
                'method' => 'sberbank',
                'logo' => 'storage/payments/sberbank.svg',
            ],
            [
                'operator' => 'yandex',
                'method' => 'alfabank',
                'logo' => 'storage/payments/alfa.svg',
            ],
            [
                'operator' => 'yandex',
                'method' => 'tinkoff_bank',
                'logo' => 'storage/payments/tinkoff.svg',
            ],
        );

        foreach ($payments as $payment) {
            PaymentMethod::firstOrCreate(
                ['method' => $payment['method'], 'operator' => $payment['operator']],
                $payment
            );
        }
    }
}
