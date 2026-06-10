<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';
    protected $fillable = ['key', 'name', 'subject', 'body', 'variables'];

    protected $casts = [
        'id'        => 'integer',
        'key'       => 'string',
        'name'      => 'string',
        'subject'   => 'string',
        'body'      => 'string',
        'variables' => 'array',
    ];

    public function render(array $vars = []): string
    {
        $search  = array_map(fn($k) => '{{' . $k . '}}', array_keys($vars));
        $replace = array_values($vars);
        return str_replace($search, $replace, $this->body);
    }
}
