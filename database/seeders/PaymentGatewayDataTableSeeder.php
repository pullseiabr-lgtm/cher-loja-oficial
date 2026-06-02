<?php

namespace Database\Seeders;

use App\Enums\GatewayMode;
use App\Enums\Activity;
use App\Models\GatewayOption;
use App\Models\PaymentGateway;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class PaymentGatewayDataTableSeeder extends Seeder
{

    public array $gateways = [
        [
            "slug"    => "paypal",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'paypal_app_id',
                    "value"  => 'YOUR_PAYPAL_APP_ID',
                ],
                [
                    "option" => 'paypal_client_id',
                    "value"  => 'YOUR_PAYPAL_CLIENT_ID'
                ],
                [
                    "option" => 'paypal_client_secret',
                    "value"  => 'YOUR_PAYPAL_CLIENT_SECRET'
                ],
                [
                    "option" => 'paypal_mode',
                    "value"  => GatewayMode::SANDBOX
                ],
                [
                    "option" => 'paypal_status',
                    "value"  => Activity::ENABLE
                ],
            ]
        ],
        [
            "slug"    => "stripe",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'stripe_key',
                    "value"  => 'YOUR_STRIPE_PUBLIC_KEY',
                ],
                [
                    "option" => 'stripe_secret',
                    "value"  => 'YOUR_STRIPE_SECRET_KEY',
                ],
                [
                    "option" => 'stripe_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'stripe_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "flutterwave",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'flutterwave_public_key',
                    "value"  => 'YOUR_FLUTTERWAVE_PUBLIC_KEY',
                ],
                [
                    "option" => 'flutterwave_secret_key',
                    "value"  => 'YOUR_FLUTTERWAVE_SECRET_KEY',
                ],
                [
                    "option" => 'flutterwave_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'flutterwave_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "paystack",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'paystack_public_key',
                    "value"  => 'YOUR_PAYSTACK_PUBLIC_KEY',
                ],
                [
                    "option" => 'paystack_secret_key',
                    "value"  => 'YOUR_PAYSTACK_SECRET_KEY',
                ],
                [
                    "option" => 'paystack_payment_url',
                    "value"  => 'https://api.paystack.co',
                ],
                [
                    "option" => 'paystack_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'paystack_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "sslcommerz",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'sslcommerz_store_name',
                    "value"  => 'YOUR_STORE_NAME',
                ],
                [
                    "option" => 'sslcommerz_store_id',
                    "value"  => 'YOUR_STORE_ID',
                ],
                [
                    "option" => 'sslcommerz_store_password',
                    "value"  => 'YOUR_STORE_PASSWORD',
                ],
                [
                    "option" => 'sslcommerz_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'sslcommerz_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "mollie",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'mollie_api_key',
                    "value"  => 'YOUR_MOLLIE_API_KEY',
                ],
                [
                    "option" => 'mollie_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'mollie_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "senangpay",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'senangpay_merchant_id',
                    "value"  => 'YOUR_SENANGPAY_MERCHANT_ID',
                ],
                [
                    "option" => 'senangpay_secret_key',
                    "value"  => 'YOUR_SENANGPAY_SECRET_KEY',
                ],
                [
                    "option" => 'senangpay_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'senangpay_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "bkash",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'bkash_app_key',
                    "value"  => 'YOUR_BKASH_APP_KEY',
                ],
                [
                    "option" => 'bkash_app_secret',
                    "value"  => 'YOUR_BKASH_APP_SECRET',
                ],
                [
                    "option" => 'bkash_username',
                    "value"  => 'YOUR_BKASH_USERNAME'
                ],
                [
                    "option" => 'bkash_password',
                    "value"  => 'YOUR_BKASH_PASSWORD'
                ],
                [
                    "option" => 'bkash_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'bkash_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "paytm",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'paytm_merchant_id',
                    "value"  => 'YOUR_PAYTM_MERCHANT_ID',
                ],
                [
                    "option" => 'paytm_merchant_key',
                    "value"  => 'YOUR_PAYTM_MERCHANT_KEY',
                ],
                [
                    "option" => 'paytm_merchant_website',
                    "value"  => 'WEBSTAGING',
                ],
                [
                    "option" => 'paytm_channel',
                    "value"  => 'WEB',
                ],
                [
                    "option" => 'paytm_industry_type',
                    "value"  => 'Retail',
                ],
                [
                    "option" => 'paytm_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'paytm_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "razorpay",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'razorpay_key',
                    "value"  => 'YOUR_RAZORPAY_KEY',
                ],
                [
                    "option" => 'razorpay_secret',
                    "value"  => 'YOUR_RAZORPAY_SECRET',
                ],
                [
                    "option" => 'razorpay_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'razorpay_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "mercadopago",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'mercadopago_access_token',
                    "value"  => 'YOUR_MERCADOPAGO_ACCESS_TOKEN',
                ],
                [
                    "option" => 'mercadopago_client_id',
                    "value"  => 'YOUR_MERCADOPAGO_CLIENT_ID',
                ],
                [
                    "option" => 'mercadopago_client_secret',
                    "value"  => 'YOUR_MERCADOPAGO_CLIENT_SECRET',
                ],
                [
                    "option" => 'mercadopago_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'mercadopago_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "cashfree",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'cashfree_app_id',
                    "value"  => 'YOUR_CASHFREE_APP_ID',
                ],
                [
                    "option" => 'cashfree_secret_key',
                    "value"  => 'YOUR_CASHFREE_SECRET_KEY',
                ],
                [
                    "option" => 'cashfree_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'cashfree_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "payfast",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'payfast_merchant_id',
                    "value"  => 'YOUR_PAYFAST_MERCHANT_ID',
                ],
                [
                    "option" => 'payfast_merchant_key',
                    "value"  => 'YOUR_PAYFAST_MERCHANT_KEY',
                ],
                [
                    "option" => 'payfast_passphrase',
                    "value"  => 'YOUR_PAYFAST_PASSPHRASE',
                ],
                [
                    "option" => 'payfast_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'payfast_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "skrill",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'skrill_merchant_email',
                    "value"  => 'YOUR_SKRILL_MERCHANT_EMAIL',
                ],
                [
                    "option" => 'skrill_merchant_api_password',
                    "value"  => 'YOUR_SKRILL_API_PASSWORD',
                ],
                [
                    "option" => 'skrill_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'skrill_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "phonepe",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'phonepe_client_id',
                    "value"  => 'YOUR_PHONEPE_CLIENT_ID',
                ],
                [
                    "option" => 'phonepe_merchant_user_id',
                    "value"  => 'YOUR_PHONEPE_MERCHANT_USER_ID',
                ],
                [
                    "option" => 'phonepe_key_index',
                    "value"  => '1',
                ],
                [
                    "option" => 'phonepe_secret_key',
                    "value"  => 'YOUR_PHONEPE_SECRET_KEY',
                ],
                [
                    "option" => 'phonepe_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'phonepe_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "iyzico",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'iyzico_api_key',
                    "value"  => 'YOUR_IYZICO_API_KEY',
                ],
                [
                    "option" => 'iyzico_secret_key',
                    "value"  => 'YOUR_IYZICO_SECRET_KEY',
                ],
                [
                    "option" => 'iyzico_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'iyzico_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "pesapal",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'pesapal_consumer_key',
                    "value"  => 'YOUR_PESAPAL_CONSUMER_KEY',
                ],
                [
                    "option" => 'pesapal_consumer_secret',
                    "value"  => 'YOUR_PESAPAL_CONSUMER_SECRET',
                ],
                [
                    "option" => 'pesapal_ipn_id',
                    "value"  => 'YOUR_PESAPAL_IPN_ID',
                ],
                [
                    "option" => 'pesapal_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'pesapal_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"   => "midtrans",
            "status" => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'midtrans_server_key',
                    "value"  => 'YOUR_MIDTRANS_SERVER_KEY',
                ],
                [
                    "option" => 'midtrans_client_key',
                    "value"  => 'YOUR_MIDTRANS_CLIENT_KEY',
                ],
                [
                    "option" => 'midtrans_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'midtrans_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"   => "twocheckout",
            "status" => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'twocheckout_seller_id',
                    "value"  => 'YOUR_2CHECKOUT_SELLER_ID',
                ],
                [
                    "option" => 'twocheckout_secret_key',
                    "value"  => 'YOUR_2CHECKOUT_SECRET_KEY',
                ],
                [
                    "option" => 'twocheckout_buy_link_secret_word',
                    "value"  => 'YOUR_2CHECKOUT_BUY_LINK_SECRET',
                ],
                [
                    "option" => 'twocheckout_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'twocheckout_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"   => "myfatoorah",
            "status" => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'myfatoorah_api_key',
                    "value"  => 'YOUR_MYFATOORAH_API_KEY',
                ],
                [
                    "option" => 'myfatoorah_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'myfatoorah_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "easypaisa",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'easypaisa_store_id',
                    "value"  => 'YOUR_EASYPAISA_STORE_ID',
                ],
                [
                    "option" => 'easypaisa_hash_key',
                    "value"  => 'YOUR_EASYPAISA_HASH_KEY',
                ],
                [
                    "option" => 'easypaisa_username',
                    "value"  => 'YOUR_EASYPAISA_USERNAME',
                ],
                [
                    "option" => 'easypaisa_password',
                    "value"  => 'YOUR_EASYPAISA_PASSWORD',
                ],
                [
                    "option" => 'easypaisa_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'easypaisa_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ]
    ];

    public function run(): void
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            foreach ($this->gateways as $gateway) {
                $payment = PaymentGateway::where(['slug' => $gateway['slug']])->first();
                if ($payment) {
                    $payment->status = $gateway['status'];
                    $payment->save();
                }
                $this->gatewayOption($gateway['options']);
            }
        }
    }

    public function gatewayOption($options): void
    {
        if (!blank($options)) {
            foreach ($options as $option) {
                $gatewayOption = GatewayOption::where(['option' => $option['option']])->first();
                if ($gatewayOption) {
                    $gatewayOption->value = $option['value'];
                    $gatewayOption->save();
                }
            }
        }
    }
}
