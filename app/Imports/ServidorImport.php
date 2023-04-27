<?php

namespace App\Imports;

use App\Models\Pessoa;
use App\Models\Servidor;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ServidorImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $nome;
    protected $cpf;
    protected $matricula;
    protected $consignante_id;
    protected $ativo;


    public function __construct($nome, $cpf, $matricula, $consignante_id, $ativo)
    {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->matricula = $matricula;
        $this->consignante_id = $consignante_id;
        $this->ativo = $ativo;
    }

    public function model(array $row)
    {

        if ($this->nome) {
            $nome = preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->nome]);
        }
        $cpf = limpa_corrige_cpf($row[$this->cpf]);

        if ($this->matricula) {
            $matricula = intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->matricula]));
        }
        if ($this->ativo) {
            $ativo = $row[$this->ativo];
        } else {
            $ativo = 1;
        }


        $pessoa = Pessoa::where('cpf', $cpf)->first();


        //dd($pessoa);
        if ($pessoa) {
            // Check if Servidor record exists for given Matricula
            $servidorExists = $pessoa->servidors->where('matricula', $matricula)->count() > 0;

            if (!$servidorExists) {
                // Create new Servidor record
                $servidor = new Servidor([
                    'matricula' => $matricula,
                    'consignante_id' => $this->consignante_id,
                    'ativo' => $ativo,
                    'averbador_id'=> 2
                ]);

                $pessoa->servidors()->save($servidor);
            } else {
                $servidor = Servidor::where('pessoa_id', $pessoa->id)->where('matricula', $matricula)->first();

                if ($servidor->ativo != $ativo) {
                    $servidor->fill(['ativo' => $ativo]);
                    $servidor->save();
                }
            }
        } else {
            // Log error to file
            $filename = Carbon::now()->format('d-m-Y-H-i-s') . '.txt';
            $content = implode(',', $row) . PHP_EOL;
            file_put_contents($filename, $content, FILE_APPEND);
            return null;
        }

    }

    public function chunkSize(): int
    {

        return 1000;
        // TODO: Implement chunkSize() method.
    }
}
