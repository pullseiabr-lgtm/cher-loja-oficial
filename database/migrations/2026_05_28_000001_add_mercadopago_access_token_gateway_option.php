<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Enums\InputType;

return new class extends Migration
{
    public function up(): void
    {
        // Find the mercadopago gateway
        $gateway = DB::table('payment_gateways')->where('slug', 'mercadopago')->first();
        if (!$gateway) {
            return;
        }

        // Only insert if not already present
        $exists = DB::table('gateway_options')
            ->where('model_type', 'App\\Models\\PaymentGateway')
            ->where('model_id', $gateway->id)
            ->where('option', 'mercadopago_access_token')
            ->exists();

        if (!$exists) {
            DB::table('gateway_options')->insert([
                'model_type' => 'App\\Models\\PaymentGateway',
                'model_id'   => $gateway->id,
                'option'     => 'mercadopago_access_token',
                'value'      => '',
                'type'       => InputType::TEXT,
                'activities' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $gateway = DB::table('payment_gateways')->where('slug', 'mercadopago')->first();
        if ($gateway) {
            DB::table('gateway_options')
                ->where('model_type', 'App\\Models\\PaymentGateway')
                ->where('model_id', $gateway->id)
                ->where('option', 'mercadopago_access_token')
                ->delete();
        }
    }
};
