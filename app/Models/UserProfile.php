<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'phone', 'job_title'];

    /**
     * Obtém o usuário proprietário deste registro.
     *
     * Define o relacionamento inverso de um para um ou um para muitos.
     * O Laravel buscará a chave estrangeira 'user_id' na tabela atual
     * para localizar o registro correspondente na tabela 'users'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
