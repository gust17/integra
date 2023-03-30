@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{route('servidor.import')}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="">Arquivo</label>
                        <input class="form-control" type="file" name="file">
                    </div>
                    <div class="form-group">
                        <label>Coluna com Nomes</label>
                        <input class="form-control" type="text" name="campo1">
                    </div>
                    <div class="form-group">
                        <label>Coluna com CPF</label>
                        <input class="form-control" type="text" name="campo2">
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
        $(document).ready(function () {

            $('#consignante_master').DataTable();
        });
    </script>
@stop
