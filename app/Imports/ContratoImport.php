<?php

namespace App\Imports;

use App\Models\Averbador;
use App\Models\Consignataria;
use App\Models\Contrato;
use App\Models\Pessoa;
use App\Models\Servidor;
use App\Services\ContratoService;
use App\Services\PessoaService;
use App\Services\ServidorService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpParser\Node\Stmt\DeclareDeclare;

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
        $consignantaria_id,


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

        $pessoaService = new PessoaService();
        $servidorService = new ServidorService();
        $contratoService = new ContratoService();

        $cpf = limpa_corrige_cpf($row[$this->cpf]);
        $nome = $row[$this->nome];
        $matricula = intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->matricula]));
        $obs = $row[$this->obs];
        $valor_parcela = corrige_dinheiro($row[$this->valor_parcela]);
        $contrato = $this->n_contrato ? preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->n_contrato]) : 0;
        $cod_verba = $this->cod_verba ? preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->cod_verba]) : 0;

        if ($this->parcela_atual) {
            $parcela_atual = valida_parcela($row[$this->parcela_atual]) ?: 1;
        } else {
            $parcela_atual = $row[$this->prazo_total] - $row[$this->prazo_remanescente] + 1;
        }

        $total_parcela = $row[$this->prazo_total];
        $valor_liberado = $total_parcela * $valor_parcela;
        $valor_devedor = $valor_liberado - ($valor_parcela * $parcela_atual);

        $consignataria = Consignataria::find($this->consignantaria_id);
        $servidor = Servidor::where('matricula', $matricula)->first();

        if (!$servidor) {
            $pessoa = $pessoaService->buscaPessoa($cpf) ?: $pessoaService->createPessoa($nome, $cpf, ativo: 0);
            $servidor = $servidorService->createServidor($pessoa->id, $matricula, $this->consignante_id, 0);
        }

        $pessoa = $servidor->pessoa;
        $contrato_semelhante = $contratoService->contratoSemelhante($pessoa->servidors->pluck('id')->toArray(), $valor_parcela, 1,$this->consignantaria_id);
        $contratoService->createContrato(
            $servidor->id,
            $this->consignantaria_id,
            $valor_parcela,
            $contrato,
            null,
            $total_parcela,
            $parcela_atual,
            $valor_liberado,
            $valor_devedor,
            $cod_verba,
            $contrato_semelhante ? $contrato_semelhante->id : null,
            $this->averbador_id,
            0,
            0,
            $obs
        );

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
