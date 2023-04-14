@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
    <h1>Arquivo padrao Infoconsig</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ url('testeimport') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Arquivo TXT</label>
                    <input class="form-control" type="file" name="file" id="">
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
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Nome</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="name_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="name_tamanho" type="number"
                                                                            class="form-control"></div>


                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo CPF</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="cpf_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="cpf_tamanho" type="number"
                                                                            class="form-control"></div>


                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Matricula</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="matricula_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="matricula_tamanho" type="number"
                                                                            class="form-control"></div>


                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Orgão</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="orgao_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="orgao_tamanho" type="number"
                                                                            class="form-control"></div>


                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Nascimento</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="nascimento_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="nascimento_tamanho" type="number"
                                                                            class="form-control"></div>


                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Admissão</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="admissao_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="admissao_tamanho" type="number"
                                                                            class="form-control"></div>


                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Recissao</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="recisao_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="recisao_tamanho" type="number"
                                                                            class="form-control"></div>


                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Data Afastamento</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="dtafastamento_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="dtafastamento_tamanho" type="number"
                                                                            class="form-control"></div>


                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Descontos Obrigatorios</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="descObrigatorio_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="descObrigatorio_tamanho" type="number"
                                                                            class="form-control"></div>


                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Forma de contratação</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="formaContrato_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="formaContrato_tamanho" type="number"
                                                                            class="form-control"></div>


                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Lotação</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="lotacao_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="lotacao_tamanho" type="number"
                                                                            class="form-control"></div>


                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Afastemanto</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="afastamento_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="afastamento_tamanho" type="number"
                                                                            class="form-control"></div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Adicionais</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="adicional_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="adicional_tamanho" type="number"
                                                                            class="form-control"></div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Adicionais</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="adicional_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="adicional_tamanho" type="number"
                                                                            class="form-control"></div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Desconto Extra</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="descExtra_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="descExtra_tamanho" type="number"
                                                                            class="form-control"></div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Salario Base</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="salBase_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="salBase_tamanho" type="number"
                                                                            class="form-control"></div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Margem</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="margem_inicio" type="number" class="form-control"></div>
                    <div class="col-6"><label for="">Tamanho</label> <input name="margem_tamanho" type="number"
                                                                            class="form-control"></div>
                </div>


                <div class="form-group">
                    <button class="btn btn-success">Carregar</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('plugins.Datatables', true)
@section('plugins.Select2', true)


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
                        $('#consignante').html('<option value="">Não foram encontrados bairros</option>').hide();
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    </script>
    <script>
        console.log('Hi!');
    </script>

    <script>
        $(document).ready(function () {

            $('#consignatarias').DataTable();
            $('#bancos').select2();
        });
    </script>
@stop
