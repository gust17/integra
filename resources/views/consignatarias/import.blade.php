@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Importar Consignatarias</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Migrar com Arquivo</h3>
            </div>
            <div class="card-body">
                <form action="{{route('consignataria.import')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Arquivo</label>
                        <input class="form-control" type="file" name="file">
                    </div>

                    <div class="form-group">
                        <label>Coluna com Consignatarias</label>
                        <input class="form-control" type="text" name="consignataria">
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

@stop
