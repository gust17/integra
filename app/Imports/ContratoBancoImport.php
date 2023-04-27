<?php

namespace App\Imports;

use App\Models\Contrato;
use App\Models\Pessoa;
use App\Models\Servidor;
use App\Services\ContratoService;
use App\Services\PessoaService;
use App\Services\ServidorService;
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

        //  dd($row);
        // dd($row);
        $pessoaService = new PessoaService();
        $servidorService = new ServidorService();
        $contratoService = new ContratoService();
        $prazo_total = $this->prazo_total ? $row[$this->prazo_total] : 0;

        if ($this->parcela_atual) {
            $prestacao_atual = valida_parcela($row[$this->parcela_atual]);
        } else {
            if ($this->prazo_remanescente) {
                $prestacao_atual = $row[$this->prazo_total] - $row[$this->prazo_remanescente] + 1;
            } else {
                $prestacao_atual = 0;
            }
        }
        //dd($prestacao_atual);
        $cod_verba = empty($this->cod_verba) ? 0 : preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->cod_verba]);

        $cpf = limpa_corrige_cpf($row[$this->cpf]);

        $pessoa = Pessoa::where('cpf', $cpf)->first();

        $valor_desconto = corrige_dinheiro2($row[$this->valor_parcela]);

        if ($this->data_efetivacao) {
            $data_contratacao = valida_data($row[$this->data_efetivacao]);
        } else {
            $data_contratacao = Carbon::now();
        }
        if ($this->valor_financiado) {
            $valor_financiado = corrige_dinheiro($row[$this->valor_financiado]);
        } else {
            $valor_financiado = 0;
        }
        $n_contrato = valida_contrato($row[$this->n_contrato]);

        $total_parcela = $prazo_total;

        $valor_liberado = $valor_desconto * $total_parcela;

        $valor_devedor = $valor_liberado - ($valor_desconto * $prestacao_atual);
        if ($this->matricula) {
            $matricula = intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->matricula]));
        } else {
            $matricula = $cpf;
        }
        $nome = valida_nome($row[$this->nome]);
        $servidor = Servidor::where('matricula', $matricula)->first();



        if (!$servidor) {
            $pessoa = $pessoaService->buscaPessoa($cpf) ?: $pessoaService->createPessoa($nome, $cpf, ativo: 0);

            $servidor = $servidorService->createServidor($pessoa->id, $matricula, $this->consignante_id, 0, $this->averbador_id);
        }




        $contrato = \App\Models\Contrato::where('origem', 0)
            ->where('valor_parcela', floatval($valor_desconto))
            ->where('averbador_id', $this->averbador_id)
            ->whereHas('servidor.pessoa', function ($query) use ($servidor) {
                $query->where('id', $servidor->pessoa->id);
            })->first();


        if ($contrato) {

            if ($contrato->total_parcela != $prazo_total || $contrato->n_parcela_referencia != $prestacao_atual || $contrato->servidor_id != $servidor->id) {
                $contratoService->createContrato(
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
                    0,
                    '1',
                    null,
                    json_encode($row)

                );
            } else {

                $ativo = 1;
                //dd('aqui');
                if ($pessoa->ativo == 0) {
                    $ativo = 0;
                }
                if ($servidor->ativo == 0) {
                    $ativo = 0;
                }
                if ($contrato->servidor->ativo == 0) {
                    $ativo = 0;
                }
                if ($contrato->obs != null) {
                    $ativo = 0;
                }


                $contratoAtualiza = $contratoService->createContrato(
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
                    $ativo,
                    1,
                    null,
                    json_encode($row)

                );

                //dd($contratoAtualiza);

                $atualiza = [
                    'contrato_id' => $contratoAtualiza->id,
                    'contrato' => $n_contrato,
                    'status' => $ativo
                ];
                $contrato->update($atualiza);
            }


        } else {
            echo 'saiu<br>';

            $contratoService->createContrato(
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
                $servidor->averbador_id,
                0,
                1,
                null,
                json_encode($row)
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
        $contratograva = Contrato::create([
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
        ]);
        //  dd($contratograva);

        return $contratograva;


    }


}
