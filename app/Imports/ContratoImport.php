<?php

namespace App\Imports;

use App\Models\Consignataria;
use App\Models\Contrato;
use App\Models\Servidor;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ContratoImport implements ToModel, WithHeadingRow, WithChunkReading

{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $cpf;

    protected $matricula;

    protected $nm_consignataria;

    protected $valor_parcela;
    protected $parcela_atual;
    protected $cod_verba;
    protected $prazo_total;
    protected $n_contrato;
    protected $data_efetivacao;
    protected $data_primeiro_desconto;
    protected $data_ultimo_desconto;
    protected $valor_liberado;
    protected $valor_financiado;
    protected $total_saldo_devedor;


    public function __construct(
        $cpf,
        $matricula,
        $nm_consignataria,
        $valor_parcela,
        $parcela_atual,
        $cod_verba,
        $prazo_total,
        $n_contrato,
        $data_efetivacao,
        $data_primeiro_desconto,
        $data_ultimo_desconto,
        $valor_liberado,
        $valor_financiado,
        $total_saldo_devedor,

    )
    {
        $this->cpf = $cpf;
        $this->matricula = $matricula;
        $this->nm_consignataria = $nm_consignataria;
        $this->valor_parcela = $valor_parcela;
        $this->parcela_atual = $parcela_atual;
        $this->cod_verba = $cod_verba;
        $this->prazo_total = $prazo_total;
        $this->n_contrato = $n_contrato;
        $this->data_efetivacao = $data_efetivacao;
        $this->data_primeiro_desconto = $data_primeiro_desconto;
        $this->data_ultimo_desconto = $data_ultimo_desconto;
        $this->valor_liberado = $valor_liberado;
        $this->valor_financiado = $valor_financiado;
        $this->total_saldo_devedor = $total_saldo_devedor;
    }

    public function model(array $row)
    {

        //dd($row);
        $cpf = preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->cpf]);

        $matricula = intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->matricula]));
        $consignataria = str_replace(['"', '='], '', $row[$this->nm_consignataria]);

        $valor_parcela = floatval(str_replace(',', '.', $row[$this->valor_parcela]));

        //dd($valor_parcela);

        if (empty($this->n_contrato)) {
            $contrato = 0;

        } else {
            $contrato = preg_replace('/[^a-zA-Z0-9\s]/', '', $this->n_contrato);
        }


        $cod_verba = preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->cod_verba]);

        // dd($cod_verba);
        $parcela_atual = $row[$this->parcela_atual];

        // dd($this->parcela_atual);
        $total_parcela = $row[$this->prazo_total];


        $valor_liberado = $total_parcela * $valor_parcela;

       // dd($valor_liberado);
        $valor_devedor = $valor_liberado - ($valor_parcela * $parcela_atual);
        //  dd($total_parcela);

        //dd($valor_parcela);
        $consignataria = Consignataria::where('name', $consignataria)->first();

        if (!$consignataria) {
            $filename = Carbon::now()->format('d-m-Y-H-i-s') . 'contratos.txt';
            $content = implode(',', $row) . PHP_EOL;
            file_put_contents($filename, $content, FILE_APPEND);
            return null;
        }
        // dd($consignataria);
        // dd($consignataria);

        $servidor = Servidor::where('matricula', $matricula)->first();

        if (!$servidor) {
            $filename = Carbon::now()->format('d-m-Y-H-i-s') . 'servidors.txt';
            $content = implode(',', $row) . PHP_EOL;
            file_put_contents($filename, $content, FILE_APPEND);
            return null;
        }

        if ($servidor->pessoa->where('cpf', $cpf)->exists()) {
            $grava = [
                'servidor_id' => $servidor->id,
                'consignataria_id' => $consignataria->id,
                'valor_parcela' => $valor_parcela,
                'contrato' => $contrato,
                'data_efetivacao' => null,
                'total_parcela' => $total_parcela,
                'n_parcela_referencia' => $parcela_atual,
                'primeira_parcela' => null,
                'ultima_parcela' => null,
                'valor_liberado' => $valor_liberado,
                'valor_total_financiado' => $valor_liberado,
                'valor_saldo_devedor' => $valor_devedor,
                'cod_verba' => $cod_verba,
            ];

            //dd($grava);
            return Contrato::create($grava);
        };

        echo 'oi<br>';
        //dd($row[$this->campo2]);

    }

    public function chunkSize(): int
    {

        return 1000;
        // TODO: Implement chunkSize() method.
    }
}
