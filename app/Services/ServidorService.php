<?php

namespace App\Services;

use App\Models\Servidor;

class ServidorService
{
    public function createServidor($pessoa, $matricula, $consignante, $ativo, $averbador)
    {
        $servidor = Servidor::create([
            'matricula' => $matricula,
            'pessoa_id' => $pessoa,
            'consignante_id' => $consignante,
            'ativo' => $ativo,
            'averbador_id' => $averbador
        ]);

        return $servidor;
    }
}
