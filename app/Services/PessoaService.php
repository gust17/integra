<?php

namespace App\Services;

use App\Models\Pessoa;

class PessoaService
{

    public function buscaPessoa($cpf)
    {
        $pessoa = Pessoa::where('cpf', $cpf)->first();
        return $pessoa;
    }

    public function createPessoa($nome, $cpf, $ativo)
    {
        $pessoa = Pessoa::create(['name' => $nome, 'cpf' => $cpf, 'ativo' => $ativo]);
        return $pessoa;
    }

}
