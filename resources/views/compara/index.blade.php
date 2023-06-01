@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
    <h1>Geral</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Insirar os arquivos</h3>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">

                @csrf
                <div class="form-group">
                    <label for="">Remessa</label>
                    <input type="file" name="remessa" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Retorno</label>
                    <input type="file" name="retorno" class="form-control">
                </div>
                <button class="brn btn-sucess">Salvar</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Insirar os arquivos</h3>
        </div>
        <div class="card-body">
            <form action="{{url('comparacaoarquivosfloripa')}}" method="post" enctype="multipart/form-data">

                @csrf
                <div class="form-group">
                    <label for="">Remessa</label>
                    <input type="file" name="remessa" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Retorno</label>
                    <input type="file" name="retorno" class="form-control">
                </div>
                <button class="brn btn-sucess">Salvar</button>
            </form>
        </div>
    </div>



@endsection
