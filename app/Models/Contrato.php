<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;


    protected $fillable = [
        'servidor_id',
        'consignataria_id',
        'contrato',
        'valor_parcela',
        'data_efetivacao',
        'total_parcela',
        'n_parcela_referencia',
        'primeira_parcela',
        'ultima_parcela',
        'valor_liberado',
        'valor_total_financiado',
        'valor_saldo_devedor',
        'cod_verba',
        'obs',
        'status',
        'valor_semelhante',
        'matricula_semelhante',
        'parcela_total',
        'valor_desconto_semelhante',
        'pessoa_existente',
        'servidor_existente',
        'contrato_id',
        'averbador_id'
    ];

    public function servidor()
    {
        return $this->belongsTo(Servidor::class);
    }

    public function valorParcelaFormatado()
    {
        return format_currency($this->attributes['valor_parcela']);
    }

    public function semelhante()
    {
        return $this->belongsTo(Contrato::class,'contrato_id','id');
    }

    public function getSemelhante($id)
    {
        $contrato = Contrato::find($id); // Obtem o contrato pelo ID

        $servidors = $contrato->servidor->pessoa->servidors->pluck('id')->toArray(); // Obtém os IDs de todos os servidores da pessoa associada ao contrato
        $valor_parcela = $contrato->valor_parcela;

        $contrato_semelhante = Contrato::whereIn('servidor_id', $servidors)
            ->whereBetween('valor_parcela', [$valor_parcela - 1, $valor_parcela + 1])
            ->where('id', '!=', $contrato->id)
            ->with('servidor.pessoa')
            ->first(); // Obtém o primeiro contrato que pertence a um dos servidores da pessoa associada ao contrato atual e que tem o mesmo valor de parcela, mas não é o contrato atual

        return $contrato_semelhante;

    }


}
