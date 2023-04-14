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


    public function __construct($nome, $cpf, $matricula, $consignante_id)
    {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->matricula = $matricula;
        $this->consignante_id = $consignante_id;
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

// Check if Pessoa record exists for given CPF
        $pessoa = Pessoa::where('cpf', $cpf)->first();

        if ($pessoa) {
            // Check if Servidor record exists for given Matricula
            $servidorExists = $pessoa->servidors->where('matricula', $matricula)->count() > 0;

            if (!$servidorExists) {
                // Create new Servidor record
                $servidor = new Servidor([
                    'matricula' => $matricula,
                    'consignante_id' => $this->consignante_id
                ]);

                $pessoa->servidors()->save($servidor);
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
