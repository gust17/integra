@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
    <h1>Migração Contratos Bancos</h1>
@stop

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{route('contratos.bancos')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="">Consignataris</label>
                        <select class="form-control bancos" name="consignataria_id" id="bancos">
                            @forelse($consignatarias as $consignataria)
                                <option value="{{$consignataria->id}}">{{$consignataria->name}}</option>

                            @empty
                            @endforelse
                        </select>

                    </div>

                    <div class="form-group">
                        <label for="">Arquivo</label>
                        <input class="form-control" type="file" name="file">
                    </div>
                    <div class="form-group">
                        <label>Coluna com Nomes</label>
                        <input class="form-control" type="text" name="nome">
                    </div>
                    <div class="form-group">
                        <label>Coluna com CPF</label>
                        <input class="form-control" type="text" name="cpf">
                    </div>
                    <div class="form-group">
                        <label>Coluna com Matriculas</label>
                        <input class="form-control" type="text" name="matricula">
                    </div>
                    <div class="form-group">
                        <label>Coluna com Valor Parcela</label>
                        <input class="form-control" type="text" name="valor_parcela">
                    </div>
                    <div class="form-group">
                        <label>Coluna com N Parcela</label>
                        <input class="form-control" type="text" name="parcela_atual">
                    </div>

                    <div class="form-group">
                        <label>Coluna com Cod Verba</label>
                        <input class="form-control" type="text" name="cod_verba">
                    </div>
                    <div class="form-group">
                        <label>Coluna com Prazo Total</label>
                        <input class="form-control" type="text" name="prazo_total">
                    </div>

                    <div class="form-group">
                        <label>Coluna com N° Contrato</label>
                        <input class="form-control" type="text" name="n_contrato">
                    </div>

                    <div class="form-group">
                        <label>Coluna com Data de Efetivação</label>
                        <input class="form-control" type="text" name="data_efetivacao">
                    </div>


                    <div class="form-group">
                        <label>Coluna com Data Primeiro desconto</label>
                        <input class="form-control" type="text" name="data_primeiro_desconto">
                    </div>

                    <div class="form-group">
                        <label>Coluna com Data Ultimo desconto</label>
                        <input class="form-control" type="text" name="data_ultimo_desconto">
                    </div>


                    <div class="form-group">
                        <label>Coluna com Valor Liberado</label>
                        <input class="form-control" type="text" name="valor_liberado">
                    </div>

                    <div class="form-group">
                        <label>Coluna com Valor Total Financiado</label>
                        <input class="form-control" type="text" name="valor_financiado">
                    </div>

                    <div class="form-group">
                        <label>Coluna com Valor Total Devedor</label>
                        <input class="form-control" type="text" name="total_saldo_devedor">
                    </div>

                    <div class="form-group">
                        <label>Coluna com Prazo Remanescente</label>
                        <input class="form-control" type="text" name="prazo_remanescente">
                    </div>


                    <div class="form-group">
                        <button class="btn btn-success">Carregar</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
    
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('plugins.Datatables', true)
@section('plugins.Select2', true)


@section('js')
    <script> console.log('Hi!'); </script>

    <script>
        $(document).ready(function () {

            $('#consignatarias').DataTable();
            $('#bancos').select2();
        });
    </script>
@stop
