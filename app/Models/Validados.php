<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validados extends Model
{
    use HasFactory;


    protected $fillable = [
        'servidor_id',
        'consignataria_id',
        'averbador_id',
        'contrato',
        'data_efetivacao',
        'total_parcela',
        'n_parcela_referencia',
        'primeira_parcela',
        'ultima_parcela',
        'valor_liberado',
        'valor_parcela',
        'valor_total_financiado',
        'valor_saldo_devedor',
        'cod_verba',
        'obs'
    ];
}
