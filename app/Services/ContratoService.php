<?php

namespace App\Services;

use App\Models\Contrato;

class ContratoService
{

    public function createContrato(
        $servidor,
        $consignataria_id,
        $valor_desconto,
        $n_contrato,
        $data_contratacao,
        $prazo_total,
        $prestacao_atual,
        $valor_liberado,
        $valor_devedor,
        $cod_verba,
        $contrato,
        $averbador_id,
        $status,
        $origem,
        $obs

    )
    {
        $grava = [
            'servidor_id' => $servidor,
            'consignataria_id' => $consignataria_id,
            'valor_parcela' => $valor_desconto,
            'contrato' => $n_contrato,
            'data_efetivacao' => $data_contratacao,
            'total_parcela' => $prazo_total,
            'n_parcela_referencia' => $prestacao_atual,
            'valor_liberado' => $valor_liberado,
            'valor_total_financiado' => $valor_liberado,
            'valor_saldo_devedor' => $valor_devedor,
            'cod_verba' => $cod_verba,
            'contrato_id' => $contrato,
            'status' => $status,
            'averbador_id' => $averbador_id,
            'origem' => $origem,
            'obs' => $obs
        ];
        // dd($grava);

        return Contrato::create($grava);

    }

    public function update($grava)
    {
        dd($grava);
    }

    public function contratoSemelhante($servidors, $valor_parcela, $origem, $consignataria)
    {
        $contrato_semelhante = Contrato::whereIn('servidor_id', $servidors)
            ->whereBetween('valor_parcela', [$valor_parcela - 1, $valor_parcela + 1])
            ->where('origem', $origem)->where('consignataria_id', $consignataria)->first();
        return $contrato_semelhante;
    }

}
