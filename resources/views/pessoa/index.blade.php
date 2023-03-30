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
                <form action="{{route('pessoa.import')}}" method="post" enctype="multipart/form-data">
                    @csrf

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

    <div class="container">
        <div class="card">
            <div class="card-body">
                <table id="pessoas" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Pessoa</th>
                        <th>CPF</th>
                        <th>QTD MATRICULA</th>

                    </tr>
                    </thead>
                    <tbody>
                    @forelse($pessoas as $pessoa)

                        <tr>
                            <td>{{$pessoa->name}}</td>
                            <td>{{$pessoa->cpf}}</td>
                            <td>{{$pessoa->servidors->count()}}</td>
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
    <script> console.log('Hi!'); </script>

    <script>
        $(document).ready(function () {

            $('#pessoas').DataTable();
        });
    </script>
@stop
