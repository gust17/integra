<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servidor extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula',
        'pessoa_id',
        'consignante_id',
        'ativo'
    ];


    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }
}
