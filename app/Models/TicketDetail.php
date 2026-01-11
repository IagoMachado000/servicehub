<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketDetail extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'description', 'processed_data'];

    protected $casts = [
        'processed_data' => 'array', // Cast automático para JSON
    ];

    /**
     * Obtém o ticket ao qual este registro está vinculado.
     *
     * Define o relacionamento inverso. O Eloquent buscará a chave
     * estrangeira 'ticket_id' na tabela atual para localizar o
     * registro correspondente na tabela 'tickets'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
