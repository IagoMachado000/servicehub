<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'document_number'];

    /**
     * Obtém todos os projetos associados a este registro.
     *
     * Define um relacionamento de um para muitos. O Laravel presume que a
     * tabela 'projects' possui uma chave estrangeira baseada no nome
     * do modelo atual (company_id).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
