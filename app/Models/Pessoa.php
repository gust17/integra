<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{


    use HasFactory;

 //   protected $connection = 'oracle';
 //   protected $table = 'PESSOA';
    protected $fillable = [
        'name',
        'cpf',
        'ativo'
    ];


    public function servidors()
    {
        return $this->hasMany(Servidor::class);
    }
}
