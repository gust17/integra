@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Migrar com Arquivo</h3>
            </div>
            <div class="card-body">
                <form action="{{route('servidor.import')}}" method="post" enctype="multipart/form-data">
                    @csrf
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
                        <label>Coluna com Status do Servidor</label>
                        <input class="form-control" type="text" name="ativo">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success">Carregar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Migrar manualmente</h3>
            </div>
            <div class="card-body">
                <form action="{{route('pessoas.store')}}" method="post" enctype="multipart/form-data">
                    @csrf


                    <div class="form-group">
                        <label>Nome</label>
                        <input class="form-control" type="text" name="nome">
                    </div>
                    <div class="form-group">
                        <label>CPF</label>
                        <input class="form-control" type="text" name="cpf">
                    </div>
                    <div class="form-group">
                        <label>Matricula</label>
                        <input placeholder="Separa matriculas por vigulas" class="form-control" type="text" name="matricula">
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

@stop
@section('plugins.Datatables', true)


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
    </script>
    <script>
        $(document).ready(function () {

            $('#pessoas').DataTable();
        });
    </script>
@stop
