@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0">Contratos Consignantes</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{route('contrato.import')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="">Arquivo</label>
                        <input class="form-control" type="file" name="file">
                    </div>
                    <div class="form-group">
                        <label>Linha do Cabeçalho</label>
                        <input class="form-control" name="inicio" type="number">
                    </div>
                    <div class="form-group">
                        <label for="">Consignataria</label>
                        <select class="form-control" name="consignantaria_id" id="">
                            <option value=""></option>
                            @forelse($consignatarias as $consignataria)
                                <option value="{{$consignataria->id}}">{{$consignataria->name}}</option>

                            @empty
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Consignante-Master</label>
                        <select class="form-control consignante_master" name="consignante_master_id"
                                id="consignante_master">
                            <option></option>
                            @forelse($consignantes_masters as $consignantes_master)
                                <option value="{{$consignantes_master->id}}">{{$consignantes_master->name}}</option>

                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Consignantes</label>
                        <select class="form-control consignante_master" name="consignante_id"
                                id="consignante">
                            <option></option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Averbador</label>
                        <select class="form-control" name="averbador_id"
                                id="averbador">
                            <option></option>

                        </select>
                    </div>
                    <div class="row">


                        <div class="form-group col-md-3">
                            <label>Coluna com CPF</label>
                            <input class="form-control" type="text" name="cpf">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Coluna com Matriculas</label>
                            <input class="form-control" type="text" name="matricula">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Coluna com Nome</label>
                            <input class="form-control" type="text" name="nome">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Coluna com Valor Parcela</label>
                            <input class="form-control" type="text" name="valor_parcela">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Coluna com N Parcela</label>
                            <input class="form-control" type="text" name="parcela_atual">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Coluna com Cod Verba</label>
                            <input class="form-control" type="text" name="cod_verba">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Coluna com Prazo Total</label>
                            <input class="form-control" type="text" name="prazo_total">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Coluna com N° Contrato</label>
                            <input class="form-control" type="text" name="n_contrato">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Coluna com Data de Efetivação</label>
                            <input class="form-control" type="text" name=data_efetivacao>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Coluna com Data Primeiro desconto</label>
                            <input class="form-control" type="text" name="data_primeiro_desconto">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Coluna com Data Ultimo desconto</label>
                            <input class="form-control" type="text" name="data_ultimo_desconto">
                        </div>


                        <div class="form-group col-md-3">
                            <label>Coluna com Valor Liberado</label>
                            <input class="form-control" type="text" name="valor_liberado">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Coluna com Valor Total Financiado</label>
                            <input class="form-control" type="text" name="valor_financiado">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Coluna com Valor Total Devedor</label>
                            <input class="form-control" type="text" name="total_saldo_devedor">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Coluna com Prazo Remanescente</label>
                            <input class="form-control" type="text" name="prazo_remanescente">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Coluna com Observações</label>
                            <input class="form-control" type="text" name="obs">
                        </div>

                    </div>

                    <div class="form-group">
                        <button class="btn btn-block btn-success">Carregar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@stop

@section('css')

@stop



@section('js')
    <script>
        $(document).on('change', 'select#consignante_master', function () {
            var consignante_master = $(this).val();
            $.ajax({
                url: '/api/consignante_master/' + consignante_master,
                type: 'GET',
                dataType: 'json',
                success: function (dados) {
                    if (dados.length > 0) {
                        var options = '<option value="">Selecione a Consignante</option>';
                        dados.forEach(function (obj) {
                            options += '<option value="' + obj.id + '">' + obj.name + '</option>';
                        });
                        $('#consignante').html(options).show();
                    } else {
                        $('#consignante').html('<option value="">Não foram encontrados Consignantes</option>').hide();
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    </script>
    <script>
        $(document).on('change', 'select#consignante', function () {
            var consignante = $(this).val();
            $.ajax({
                url: '/api/consignante/' + consignante,
                type: 'GET',
                dataType: 'json',
                success: function (dados) {
                    if (dados.length > 0) {
                        var options = '<option value="">Selecione o Aberbador</option>';
                        dados.forEach(function (obj) {
                            options += '<option value="' + obj.id + '">' + obj.name + '</option>';
                        });
                        $('#averbador').html(options).show();
                    } else {
                        $('#averbador').html('<option value="">Não foram encontrados Averbadores</option>').hide();
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });

    </script>
    <script> console.log('Hi!'); </script>

@stop
