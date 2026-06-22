<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $benefits = [
            1 => [
                'title'       => 'Atendimento Personalizado',
                'description' => 'Suporte dedicado via WhatsApp com carinho e atenção',
            ],
            2 => [
                'title'       => 'Pagamento Seguro',
                'description' => 'Pix, cartão de crédito e boleto com total segurança',
            ],
            3 => [
                'title'       => 'Envio para Todo Brasil',
                'description' => 'Entrega rápida com código de rastreamento',
            ],
            4 => [
                'title'       => 'Qualidade Garantida',
                'description' => 'Peças selecionadas com o melhor custo-benefício',
            ],
        ];

        foreach ($benefits as $id => $data) {
            DB::table('benefits')->where('id', $id)->update($data);
        }
    }

    public function down(): void
    {
        $benefits = [
            1 => [
                'title'       => 'Professional Service',
                'description' => 'Efficient customer support from passionate team',
            ],
            2 => [
                'title'       => 'Secure Payment',
                'description' => 'Different secure payment methods',
            ],
            3 => [
                'title'       => 'Fast Delivery',
                'description' => 'Fast and convenient door to door delivery',
            ],
            4 => [
                'title'       => 'Quality & Savings',
                'description' => 'Comprehensive quality control and affordable prices',
            ],
        ];

        foreach ($benefits as $id => $data) {
            DB::table('benefits')->where('id', $id)->update($data);
        }
    }
};
