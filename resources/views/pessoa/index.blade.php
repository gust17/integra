@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Pesssoas</h1>
@stop

@section('content')
  

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
                            <td>{{$pessoa->servidors_count}}</td>
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
