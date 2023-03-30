<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignataria extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];


    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function getPorcentagem()
    {
        $calculo = 0;
        if ($this->contratos->count() != 0 ) {
            $calculo = $this->contratos->where('status', '=', 1)->count() * 100 / $this->contratos->count();
        }

        return format_porcentagem($calculo);
    }
}
