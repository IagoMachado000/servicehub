<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'user_id', 'title', 'status', 'attachment_path'];

    /**
     * Obtém o projeto ao qual este registro pertence.
     * O Laravel buscará a coluna 'project_id' na tabela atual para
     * realizar o join com a tabela 'projects'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Obtém o usuário associado a este registro.
     * O Laravel buscará a coluna 'user_id' na tabela atual para
     * realizar o join com a tabela 'users'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtém os detalhes adicionais vinculados a este registro.
     * Ao contrário do BelongsTo, aqui a chave estrangeira (ex: ticket_id)
     * deve estar localizada na tabela 'ticket_details'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(TicketDetail::class);
    }
}
