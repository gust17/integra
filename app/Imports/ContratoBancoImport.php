<?php

namespace App\Imports;

use App\Models\Contrato;
use App\Models\Pessoa;
use App\Models\Servidor;
use App\Services\ContratoService;
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
        $this->valida_pessoa = ContratoService::class;
    }

    public function model(array $row)
    {


        //dd($row);
        $prazo_total = $this->prazo_total ? $row[$this->prazo_total] : 0;

        if ($this->parcela_atual) {
            $prestacao_atual = valida_parcela($row[$this->parcela_atual]);
        } else {

            $prestacao_atual = $row[$this->prazo_total] - $row[$this->prazo_remanescente] + 1;
        }
        //dd($prestacao_atual);
        $cod_verba = empty($this->cod_verba) ? 0 : preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->cod_verba]);

        $cpf = limpa_corrige_cpf($row[$this->cpf]);

        $pessoa = Pessoa::where('cpf', $cpf)->first();

        $valor_desconto = corrige_dinheiro2($row[$this->valor_parcela]);

        //dd($row[$this->data_contratacao]);

        $data_contratacao = valida_data($row[$this->data_efetivacao]);

        $valor_financiado = corrige_dinheiro($row[$this->valor_financiado]);

        $n_contrato = valida_contrato($row[$this->n_contrato]);

        $total_parcela = $prazo_total;

        $valor_liberado = $valor_desconto * $total_parcela;

        $valor_devedor = $valor_liberado - ($valor_desconto * $prestacao_atual);

        $matricula = intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->matricula]));

        $nome = valida_nome($row[$this->nome]);
        //dd($matricula);

        $pessoa = $this->valida_pessoa($nome, $cpf);


        $servidor = $this->valida_servidor($matricula, $pessoa, $this->consignante_id);

        $contrato = Contrato::where('valor_parcela', floatval($valor_desconto))
            ->where('servidor_id', $servidor->id)
            ->where('origem', 0)->first();


        if ($contrato) {
            /*    echo $contrato->servidor->id . ' ' . $servidor->id . "<br>";
                if ($contrato->total_parcela != $prazo_total) {

                    echo $contrato->parcela_total . ' ' . $prazo_total . ' parcela total<br>';
                }
                if ($contrato->n_parcela_referencia != $prestacao_atual) {
                    echo $contrato->n_parcela_referencia . ' ' . $prestacao_atual . ' parcela atual<br>';
                }
                if ($contrato->servidor_id != $servidor->id) {
                    echo 'servidor<br>';
                }
                echo 'contrato<br>'; */
            if ($contrato->total_parcela != $prazo_total || $contrato->n_parcela_referencia != $prestacao_atual || $contrato->servidor_id != $servidor->id) {
                echo 'oi.<br>';
                $this->novo_cadastro(
                    $servidor->id,
                    $this->consignataria_id,
                    $valor_desconto,
                    $n_contrato,
                    $data_contratacao,
                    $prazo_total,
                    $prestacao_atual,
                    $valor_liberado,
                    $valor_devedor,
                    $cod_verba,
                    $contrato->id,
                    $this->averbador_id,
                    0

                );
            } else {

                echo 'igual<br>';
                //dd('aqui');
                $atualiza = [
                    'contrato' => $n_contrato,
                    'valor_total_financiado' => $valor_financiado,
                    'data_efetivacao' => $data_contratacao->format('Y-m-d'),
                    'status' => 1
                ];

                $contrato->update($atualiza);
            }


        } else {
            echo 'saiu<br>';

            $this->novo_cadastro(
                $servidor->id,
                $this->consignataria_id,
                $valor_desconto,
                $n_contrato,
                $data_contratacao,
                $prazo_total,
                $prestacao_atual,
                $valor_liberado,
                $valor_devedor,
                $cod_verba,
                null,
                $this->averbador_id,
                0
            );
            // echo 'agora<br>';
            //dd($grava);
            //  return Contrato::create($grava);
        }


        //

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

    public function valida_pessoa($nome, $cpf)
    {
        $pessoa = Pessoa::firstOrCreate([
            'name' => $nome,
            'cpf' => $cpf,
            'ativo' => 0
        ]);
        return $pessoa;
    }

    public function valida_servidor($matricula, $pessoa, $consignante)
    {

        $servidor = Servidor::where('matricula', $matricula)->first();

        if (!$servidor) {
            $servidor = Servidor::create([
                'pessoa_id' => $pessoa->id,
                'matricula' => $matricula,
                'ativo' => 0,
                'consignante_id' => $this->consignante_id
            ]);
        }

        return $servidor;
    }


    public function novo_cadastro(
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
            'primeira_parcela' => null,
            'ultima_parcela' => null,
            'valor_liberado' => $valor_liberado,
            'valor_total_financiado' => $valor_liberado,
            'valor_saldo_devedor' => $valor_devedor,
            'cod_verba' => $cod_verba,
            'contrato_id' => $contrato,
            'status' => $status,
            'averbador_id' => $averbador_id,
            'origem' => 1
        ];
        //dd($grava);

        Contrato::create($grava);

    }


}
