<?php

namespace App\Imports;

use App\Models\Contrato;
use App\Models\Pessoa;
use App\Models\Servidor;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ContratoBancoImport implements ToModel, WithHeadingRow, WithGroupedHeadingRow, WithStartRow
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $cpf;
    protected $nome;
    protected $matricula;
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
    protected $consignataria_id;

    protected $prazo_remanescente;

    protected $consignante_id;
    protected $averbador_id;


    public function toArray(array $rows)
    {

    }

    public function __construct(
        $cpf,
        $nome,
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
        $consignataria_id,
        $prazo_remanescente,
        $consignante_id,
        $averbador_id,
        $inicio


    )
    {
        $this->cpf = $cpf;
        $this->nome = $nome;
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
        $this->consignataria_id = $consignataria_id;
        $this->prazo_remanescente = $prazo_remanescente;
        $this->consignante_id = $consignante_id;
        $this->averbador_id = $averbador_id;
        $this->inicio = $inicio;
    }

    public function model(array $row)
    {

        if ($this->prazo_total) {
            $prazo_total = $row[$this->prazo_total];
        } else {
            $prazo_total = 0;
        }


        if (!$this->parcela_atual) {
            $prestaca_atual = $prazo_total - $row[$this->prazo_remanescente];
        } else {
            $prestaca_atual = $row[$this->parcela_atual];
        }

        if (empty($this->cod_verba)) {
            $cod_verba = 0;

        } else {
            $cod_verba = preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->cod_verba]);
        }
        $cpf = intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->cpf]));
        $pessoa = Pessoa::where('cpf', $cpf)->first();
        $valor_desconto = floatval(str_replace(',', '.', $row[$this->valor_parcela]));

        if ($this->data_efetivacao) {
            $data_contratacao = $row[$this->data_efetivacao];
        } else {
            $data_contratacao = Carbon::now();
        }
        if ($this->valor_financiado) {
            $valor_financiado = floatval(str_replace(',', '.', $row[$this->valor_financiado]));
        } else {
            $valor_financiado = 0;
        }

        //dd($valor_financiado);

        if ($this->n_contrato) {
            $n_contrato = intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->n_contrato]));
        } else {
            $n_contrato = 0;
        }

        $total_parcela = $prazo_total;

        $valor_liberado = $valor_desconto * $total_parcela;

        $valor_devedor = $valor_liberado - ($valor_desconto * $prestaca_atual);
        //  dd($total_parcela);


        if (empty($this->n_contrato)) {
            $contrato = 0;

        } else {
            $contrato = preg_replace('/[^a-zA-Z0-9\s]/', '', $this->n_contrato);
        }

        $matricula = intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->matricula]));
        if ($this->nome) {
            $nome = (preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->nome]));

        } else {
            $nome = "00000000000000000";
        }

        //dd($row);


        if ($this->data_efetivacao) {
            $data_contratacao = Carbon::createFromFormat('d/m/Y', $data_contratacao);
        }
        //dd($data_contratacao);

        $valor_semelhante = null;
        $matricula_semelhante = null;
        $parcela_total = null;
        $valor_desconto_semelhante = null;
        $pessoa_existente = null;
        $servidor_existente = null;


        $contrato_id = null;
        if ($pessoa) {

            $servidor = $pessoa->servidors->where('matricula', $matricula)->first();


            if (!$servidor) {
                $servidor_existente = 0;
                $servidor = Servidor::create([
                    'pessoa_id' => $pessoa->id,
                    'matricula' => $matricula,
                    'ativo' => 0,
                    'consignante_id' => $this->consignante_id
                ]);
            }


        } else {
            $pessoa_existente = 0;
            $pessoa = Pessoa::create(
                [
                    'name' => $nome,
                    'cpf' => $cpf,
                    'ativo' => 0,
                ]
            );
            $servidor = $pessoa->servidors->where('matricula', $matricula)->first();


            if (!$servidor) {
                $servidor_existente = 0;
                $servidor = Servidor::create([
                    'pessoa_id' => $pessoa->id,
                    'matricula' => $matricula,
                    'ativo' => 0,
                    'consignante_id' => $this->consignante_id
                ]);
            }


        }
        if ($total_parcela == null) {

            //dd('oi');
            echo 'nao deu';


        } else {


            $contrato = Contrato::whereNot('status', 2)->where('valor_parcela', $valor_desconto)->where('origem', 0)->where('servidor_id', $servidor->id)->first();

            //dd($contrato);
            if ($contrato) {


                //  dd($contrato->toArray(), $row);
                if ($contrato->total_parcela != $prazo_total) {

                    echo 'difente prazo total<br>';
                }
                if ($contrato->n_parcela_referencia != $prestaca_atual) {
                    echo 'difente parcela referencia<br>';
                } else {
                    //  dd($row, $contrato);
                    echo $contrato->id . "<br>";
                    $atualiza = [
                        'contrato' => $n_contrato,
                        'valor_total_financiado' => $valor_financiado,
                        'data_efetivacao' => $data_contratacao->format('Y-m-d'),
                        'status' => 1
                    ];

                    $contrato->update($atualiza);
                }


                //$contrato->update(
                //  []);
                //dd($contrato);
                echo "tem<br>";
            } else {
                $servidors = $servidor->pessoa->servidors->pluck('id')->toArray(); // Obtém os IDs de todos os servidores da pessoa associada ao contrato

                $contrato_semelhante = Contrato::whereIn('servidor_id', $servidors)
                    ->whereBetween('valor_parcela', [$valor_desconto - 1, $valor_desconto + 1])
                    ->with('servidor.pessoa')
                    ->first();

                if ($contrato_semelhante) {

                    if ($contrato_semelhante->servidor_id == $servidor->id) {

                        $matricula_semelhante = 1;
                    } else {


                        $matricula_semelhante = 0;
                    }

                    if ($contrato_semelhante->total_parcela == $total_parcela) {
                        $parcela_total = 1;
                    } else {
                        $parcela_total = 0;
                    }
                    if ($contrato_semelhante->valor_parcela == $valor_desconto) {
                        $valor_desconto_semelhante = 1;
                    } else {
                        $valor_desconto_semelhante = 0;
                    }

                    $grava = [
                        'servidor_id' => $servidor->id,
                        'consignataria_id' => $this->consignataria_id,
                        'valor_parcela' => $valor_desconto,
                        'contrato' => $n_contrato,
                        'data_efetivacao' => $data_contratacao,
                        'total_parcela' => $prazo_total,
                        'n_parcela_referencia' => $prestaca_atual,
                        'primeira_parcela' => null,
                        'ultima_parcela' => null,
                        'valor_liberado' => $valor_liberado,
                        'valor_total_financiado' => $valor_liberado,
                        'valor_saldo_devedor' => $valor_devedor,
                        'cod_verba' => $cod_verba,
                        'matricula_semelhante' => $matricula_semelhante,
                        'parcela_total' => $parcela_total,
                        'valor_desconto_semelhante' => $valor_desconto_semelhante,
                        'contrato_id' => $contrato_semelhante->id,
                        'status' => 2,
                        'pessoa_existente' => $pessoa_existente,
                        'servidor_existente' => $servidor_existente,
                        'averbador_id' => $this->averbador_id,
                        'origem' => 1
                    ];
                    //dd($grava);
                    return Contrato::create($grava);
                    //  dd($grava);
                    // dd($contrato_semelhante->servidor->pessoa->name, $servidor->pessoa->name);
                } else {
                    $grava = [
                        'servidor_id' => $servidor->id,
                        'consignataria_id' => $this->consignataria_id,
                        'valor_parcela' => $valor_desconto,
                        'contrato' => $n_contrato,
                        'data_efetivacao' => $data_contratacao,
                        'total_parcela' => $prazo_total,
                        'n_parcela_referencia' => $prestaca_atual,
                        'primeira_parcela' => null,
                        'ultima_parcela' => null,
                        'valor_liberado' => $valor_liberado,
                        'valor_total_financiado' => $valor_liberado,
                        'valor_saldo_devedor' => $valor_devedor,
                        'cod_verba' => $cod_verba,
                        'status' => 2,
                        'pessoa_existente' => $pessoa_existente,
                        'servidor_existente' => $servidor_existente,
                        'averbador_id' => $this->averbador_id,
                        'origem' => 1
                    ];
                    //dd($grava);
                    return Contrato::create($grava);
                }


                //
            }


        }
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
