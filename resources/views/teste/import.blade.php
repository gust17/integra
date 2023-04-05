@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
    <h1>Consignatarias</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ url('testeimport') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input class="form-control" type="file" name="file" id="">
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Nome</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="name_inicio" type="number" class="form-control"></div>
                    <div class="col-6">   <label for="">Termino</label> <input name="name_fim" type="number" class="form-control"></div>



                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo CPF</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="name_inicio" type="number" class="form-control"></div>
                    <div class="col-6">   <label for="">Termino</label> <input name="name_fim" type="number" class="form-control"></div>



                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="">Localização do Campo Matricula</label>
                    </div>
                    <div class="col-6">
                        <label for="">Inicio</label>
                        <input name="name_inicio" type="number" class="form-control"></div>
                    <div class="col-6">   <label for="">Termino</label> <input name="name_fim" type="number" class="form-control"></div>



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
        console.log('Hi!');
    </script>

    <script>
        $(document).ready(function() {

            $('#consignatarias').DataTable();
            $('#bancos').select2();
        });
    </script>
@stop
