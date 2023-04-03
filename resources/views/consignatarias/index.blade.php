@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
    <h1>Consignatarias</h1>
@stop

@section('content')


    <div class="container">
        <div class="card">
            <div class="card-body">
                <table id="consignatarias" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Consignatarias</th>
                        <th>Contratos</th>
                        <th>Nao Validados</th>
                        <th>Validados</th>
                        <th>Contratos</th>
                        <th>Progresso</th>

                    </tr>
                    </thead>
                    <tbody>
                    @forelse($consignatarias as $consignataria)

                        <tr>
                            <td><a href="{{route('consignatarias.show',$consignataria)}}">{{$consignataria->name}}</a> </td>
                            <td>{{$consignataria->contratos->count()}}</td>
                            <td>{{$consignataria->contratos->where('status','!=',1)->count()}}</td>
                            <td>{{$consignataria->contratos->where('status','=',1)->count()}}</td>
                            <td>{{$consignataria->contratos->count()}}</td>
                            <th><div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70"
                                         aria-valuemin="0" aria-valuemax="100" style="width:{{$consignataria->getPorcentagem()}}%">
                                        {{$consignataria->getPorcentagem()}}%
                                    </div>
                                </div></th>

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
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('plugins.Datatables', true)
@section('plugins.Select2', true)


@section('js')
    <script> console.log('Hi!'); </script>

    <script>
        $(document).ready(function () {

            $('#consignatarias').DataTable();
            $('#bancos').select2();
        });
    </script>
@stop
