@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
    <h1>Contratos</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{url('consulta')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="">Consignante-Master</label>
                            <select class="form-control consignante_master" name="consignante_master_id"
                                    id="consignante_master">
                                <option></option>
                                @forelse($consignantes_Masters as $consignantes_master)

                                    <option
                                        value="{{$consignantes_master->id}}" {{(old('consignante_master_id', $consignantes_master->id) == $consignantes_master->id ? 'selected' : '')}} > {{$consignantes_master->name}} </option>

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
                            <select class="form-control averbador" name="averbador_id"
                                    id="averbador">
                                <option></option>

                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success btn-block">Consultar</button>
                        </div>
                    </form>
                </div>
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
                        $('#consignante').html('<option value="">Não foram encontrados consignantes</option>').hide();
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
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
    <script>
        $(document).ready(function () {

            $('#consignatarias').DataTable();
            $('#bancos').select2();
        });
    </script>
@stop
