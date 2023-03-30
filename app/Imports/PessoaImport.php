<?php

namespace App\Imports;

use App\Models\Pessoa;
use App\Models\Servidor;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;


HeadingRowFormatter::default('none');

class PessoaImport implements ToModel, WithHeadingRow, WithChunkReading


{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $nome;
    protected $cpf;
    protected $matricula;


    public function __construct($nome, $cpf, $matricula)
    {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->matricula = $matricula;
    }

    public function model(array $row)
    {

        //dd($row);
        //dd($this->campo1);]

        $nome = preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->nome]);
        $cpf = preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->cpf]);
        $matricula = intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->matricula]));

        if (empty($nome)) {
            $filename = Carbon::now()->format('d-m-Y-H-i-s').'.txt';
            $content = implode(',', $row) . PHP_EOL;
            file_put_contents($filename, $content, FILE_APPEND);
            return null;
        }

        $pessoa = Pessoa::firstOrCreate([
            'name' => $nome,
            'cpf' => $cpf
        ]);

        if (!$pessoa->servidors->where('matricula', $matricula)->count()) {
            $servidor = new Servidor([
                'matricula' => $matricula
            ]);
            $pessoa->servidors()->save($servidor);
        }


        //dd($pessoa);
        return $pessoa;


        // dd($grava);

    }

    public function chunkSize(): int
    {

        return 10000;
        // TODO: Implement chunkSize() method.
    }
}
