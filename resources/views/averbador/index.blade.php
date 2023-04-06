@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{route('averbadors.store')}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="">Nome do Consignante:</label>
                        <input class="form-control" type="text" name="name">
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
                        <button class="btn btn-success">Carregar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <table id="consignante_master" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Consignantes</th>
                        <th>Consignantes Master</th>


                    </tr>
                    </thead>
                    <tbody>
                    @forelse($averbadors as $averbador)

                        <tr>
                            <td>{{$averbador->name}}</td>
                            <td>{{$averbador->consignante->name}}</td>

                        </tr>
                    @empty
                    @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop
@section('plugins.Datatables', true)


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
    <script> console.log('Hi!'); </script>

    <script>
        $(document).ready(function () {

            $('#averbador').DataTable();
        });
    </script>
@stop
