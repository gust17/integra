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
// Extract variables from the row object or set default values if they are missing
        $prazo_total = $this->prazo_total ? $row[$this->prazo_total] : 0;
        $prestaca_atual = $this->parcela_atual ? $row[$this->parcela_atual] : ($prazo_total - $row[$this->prazo_remanescente]);
        $cod_verba = empty($this->cod_verba) ? 0 : preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->cod_verba]);
        $cpf = intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->cpf]));
        $valor_desconto = floatval(str_replace(',', '.', $row[$this->valor_parcela]));
        $data_contratacao = $this->data_efetivacao ? Carbon::createFromFormat('d/m/Y', $row[$this->data_efetivacao]) : Carbon::now();
        $valor_financiado = $this->valor_financiado ? floatval(str_replace(',', '.', $row[$this->valor_financiado])) : 0;
        $n_contrato = $this->n_contrato ? intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->n_contrato])) : 0;
        $nome = $this->nome ? preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->nome]) : "00000000000000000";

// Calculate other values based on the extracted variables
        $total_parcela = $prazo_total;
        $valor_liberado = $valor_desconto * $total_parcela;
        $valor_devedor = $valor_liberado - ($valor_desconto * $prestaca_atual);
        $contrato = empty($this->n_contrato) ? 0 : preg_replace('/[^a-zA-Z0-9\s]/', '', $this->n_contrato);
        $matricula = intval(preg_replace('/[^a-zA-Z0-9\s]/', '', $row[$this->matricula]));

// Find or create the Pessoa and Servidor objects based on the given CPF and Matrícula
        $pessoa = Pessoa::where('cpf', $cpf)->first();
        if (!$pessoa) {
            $pessoa = Pessoa::create([
                'name' => $nome,
                'cpf' => $cpf,
                'ativo' => 0,
            ]);
        }
        $servidor = $pessoa->servidors->where('matricula', $matricula)->first();
        if (!$servidor) {
            $servidor = Servidor::create([
                'pessoa_id' => $pessoa->id,
                'matricula' => $matricula,
                'ativo' => 0,
                'consignante_id' => $this->consignante_id
            ]);
        }

// Check if there is an existing Contrato object that matches the given criteria
        $contrato = Contrato::whereNot('status', 2)
            ->where('valor_parcela', $valor_desconto)
            ->where('origem', 0)
            ->where('servidor_id', $servidor->id)
            ->where('total_parcela', $total_parcela)
            ->where('n_parcela_referencia', $prestaca_atual)
            ->first();

        if ($contrato) {
            echo $contrato->id . "<br>";
        } else {
            echo "Contrato não encontrado<br>";
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
