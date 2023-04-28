<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Somado extends Model
{
    use HasFactory;

    protected $fillable = [
        'consignante_id',
        'consignataria_id',
        'pessoa_id',
        'matricula',
        'valor'
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
