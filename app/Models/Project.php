<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'name', 'is_active'];

    /**
     * Obtém a empresa à qual este registro pertence.
     * Define o relacionamento inverso. O Laravel buscará pela chave
     * estrangeira 'company_id' na tabela local para associar ao
     * registro correspondente na tabela 'companies'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }


    /**
     * Obtém todos os tickets vinculados a este registro.
     * Define um relacionamento de um para muitos. O Laravel assume que
     * a tabela 'tickets' possui uma chave estrangeira baseada no
     * nome do modelo atual (project_id).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
