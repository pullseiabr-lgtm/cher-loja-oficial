<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'key'       => 'verify_email',
                'name'      => 'Verificação de E-mail',
                'subject'   => 'Verificação de E-mail',
                'body'      => '<h2 style="color:#00b398;">Verificação de E-mail</h2><p>Olá! Para confirmar seu cadastro, use o código abaixo:</p><p style="font-size:28px;font-weight:bold;letter-spacing:6px;color:#1F1F39;">{{pin}}</p><p>Não compartilhe este código com ninguém.</p>',
                'variables' => ['pin' => 'Código de verificação'],
            ],
            [
                'key'       => 'reset_password',
                'name'      => 'Redefinição de Senha',
                'subject'   => 'Redefinição de Senha',
                'body'      => '<h2 style="color:#00b398;">Redefinição de Senha</h2><p>Você solicitou a redefinição de senha. Use o código abaixo:</p><p style="font-size:28px;font-weight:bold;letter-spacing:6px;color:#1F1F39;">{{pin}}</p><p>Se não foi você quem solicitou, ignore este e-mail.</p>',
                'variables' => ['pin' => 'Código de verificação'],
            ],
            [
                'key'       => 'order_notification',
                'name'      => 'Notificação de Pedido',
                'subject'   => 'Atualização do seu pedido',
                'body'      => '<h2 style="color:#00b398;">Atualização do Pedido</h2><p>Olá, {{name}}!</p><p><strong>Pedido #{{order_id}}</strong></p><p>{{message}}</p>',
                'variables' => ['name' => 'Nome do cliente', 'order_id' => 'ID do pedido', 'message' => 'Mensagem do pedido'],
            ],
            [
                'key'       => 'order_got',
                'name'      => 'Novo Pedido Recebido',
                'subject'   => 'Você recebeu um novo pedido',
                'body'      => '<h2 style="color:#00b398;">Novo Pedido Recebido</h2><p><strong>Pedido #{{order_id}}</strong></p><p>{{message}}</p>',
                'variables' => ['order_id' => 'ID do pedido', 'message' => 'Mensagem do pedido'],
            ],
            [
                'key'       => 'subscriber',
                'name'      => 'Newsletter',
                'subject'   => 'Newsletter',
                'body'      => '<h2 style="color:#00b398;">{{title}}</h2><p>{{message}}</p>',
                'variables' => ['title' => 'Assunto', 'message' => 'Conteúdo'],
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(['key' => $template['key']], $template);
        }
    }
}
