<?php

namespace App\Imports;

use App\Models\Averbador;
use App\Models\Consignataria;
use App\Models\Contrato;
use App\Models\Pessoa;
use App\Models\Servidor;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ContratoImport implements ToModel, WithHeadingRow, WithChunkReading, WithGroupedHeadingRow, WithStartRow
    //, ShouldQueue

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

    protected $averbador_id;


    protected $nome;


    public function __construct(
        $cpf,
        $matricula,
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
        $averbador_id,
        $prazo_remanescente,
        $consignante_id,
        $inicio,
        $obs,
        $nome,
        $consignantaria_id

    )
    {
        $this->cpf = $cpf;
        $this->matricula = $matricula;
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
        $this->averbador_id = $averbador_id;
        $this->prazo_remanescente = $prazo_remanescente;
        $this->consignante_id = $consignante_id;
        $this->inicio = $inicio;
        $this->obs = $obs;
        $this->nome = $nome;
        $this->consignantaria_id = $consignantaria_id;
    }

    public function model(array $row)
    {


        //dd($row[$this->parcela_atual]);


        $cpf = limpa_corrige_cpf($row[$this->cpf]);
        $nome = $row[$this->nome];

        $matricula = intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->matricula]));


        $obs = $row[$this->obs];
        $valor_parcela = corrige_dinheiro($row[$this->valor_parcela]);

       // dd($valor_parcela);

        // dd($valor_parcela);
        if (empty($this->n_contrato)) {
            $contrato = 0;

        } else {
            $contrato = preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->n_contrato]);

        }

        if ($this->cod_verba) {
            $cod_verba = preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->cod_verba]);
        } else {
            $cod_verba = 0;
        }
        // dd($contrato);

        // dd($cod_verba);
        if ($this->parcela_atual) {
            $parcela_atual = valida_parcela($row[$this->parcela_atual]);
            if ($parcela_atual == 0) {
                $parcela_atual = 1;
            }
        } else {
            $parcela_atual = $row[$this->prazo_total] - $row[$this->prazo_remanescente] + 1;
        }

        $total_parcela = $row[$this->prazo_total];


        $valor_liberado = $total_parcela * $valor_parcela;

        // dd($valor_liberado);
        $valor_devedor = $valor_liberado - ($valor_parcela * $parcela_atual);
        //  dd($total_parcela);

        //dd($valor_parcela);
        $consignataria = Consignataria::find($this->consignantaria_id);


        if (!$consignataria) {
            $filename = Carbon::now()->format('d-m-Y-H-i-s') . 'contratos.txt';
            $content = implode(',', array_map('strval', $row)) . PHP_EOL;
            file_put_contents($filename, $content, FILE_APPEND);
            return null;
        }


        $servidor = Servidor::where('matricula', $matricula)->first();


        if (!$servidor) {
            $pessoa = Pessoa::where('cpf', $cpf)->first();
            if ($pessoa) {
                $consignante = Averbador::find($this->averbador_id)->consignante->id;

                $servidor = Servidor::create([
                    'pessoa_id' => $pessoa->id,
                    'matricula' => $matricula,
                    'ativo' => 0,
                    'consignante_id' => $this->consignante_id
                ]);
            } else {


                //$filename = Carbon::now()->format('d-m-Y-H-i-s') . 'servidors.txt';
                // /$content = implode(',', array_map('strval', $row)) . PHP_EOL;
                //file_put_contents($filename, $content, FILE_APPEND);
                //return null;


            }
            //

        }
        if ($servidor == null) {
            $pessoa = Pessoa::where('cpf', $cpf)->first();
            if (!$pessoa) {
                $pessoa = Pessoa::create(['name' => $nome, 'cpf' => $cpf, 'ativo' => 0]);
                $servidor = Servidor::create([
                    'pessoa_id' => $pessoa->id,
                    'matricula' => $matricula,
                    'ativo' => 0,
                    'consignante_id' => $this->consignante_id
                ]);
            }
        }

        if ($servidor->pessoa->where('cpf', $cpf)->exists()) {

            $servidors = $servidor->pessoa->servidors->pluck('id')->toArray();
            // dd($servidors);
            $valor_desconto = floatval(str_replace(',', '.', $row[$this->valor_parcela]));
            // dd($valor_desconto);

            $contrato_semelhante = Contrato::whereIn('servidor_id', $servidors)
                ->whereBetween('valor_parcela', [$valor_desconto - 1, $valor_desconto + 1])
                ->with('servidor.pessoa')
                ->where('origem', 1)->first();


            if ($contrato_semelhante) {
                //dd($contrato_semelhante);
                if ($contrato_semelhante->n_parcela_referencia == $parcela_atual && $contrato_semelhante->valor_parcela == $valor_parcela && $servidor->id == $contrato_semelhante->servidor_id) {
                    //dd($contrato_semelhante->n_parcela_referencia, $valor_desconto, $parcela_atual);
                    $contrato_semelhante->fill(

                        [
                            'cod_verba' => $cod_verba,
                            'status' => 1,

                        ]);
                    $contrato_semelhante->save();
                } else {
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
                        'averbador_id' => $this->averbador_id,
                        'obs' => $obs,
                        'origem' => 0
                    ];

                    //dd($grava);
                    $salvarContrato = Contrato::create($grava);
                    if ($contrato_semelhante->valor_parcela != $valor_parcela) {
                        $contrato_semelhante->update(['valor_desconto_semelhante' => 1]);
                    }
                    $contrato_semelhante->update(['contrato_id' => $salvarContrato->id]);


                }


            } else {

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
                    'averbador_id' => $this->averbador_id,
                    'obs' => $obs,
                    'origem' => 0
                ];

                //dd($grava);
                return Contrato::create($grava);
            }

        };

        echo 'oi<br>';
//dd($row[$this->campo2]);

    }

    public function chunkSize(): int
    {

        return 1000;
        // TODO: Implement chunkSize() method.
    }

    public function startRow(): int
    {
        return $this->inicio + 1;
        // TODO: Implement startRow() method.
    }

    public function headingRow(): int
    {
        return $this->inicio;
    }

}
