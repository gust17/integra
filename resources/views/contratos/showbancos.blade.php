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
                        <th>Contratos Prefeitura</th>
                        <th>Contratos Bancos</th>


                    </tr>
                    </thead>
                    <tbody>
                    @forelse($consignatarias as $consignataria)

                        <tr>
                            <td><a href="{{url("relatoriocontrato/$consignataria->id/$averbador->id")}}">{{$consignataria->name}}</a> </td>
                            <td>{{$consignataria->contratos->where('averbador_id',$averbador->id)->count()}}</td>
                            <td>{{$consignataria->contratos->where('averbador_id',$averbador->id)->where('status','!=',1)->count()}}</td>
                            <td>{{$consignataria->contratos->where('averbador_id',$averbador->id)->where('status','=',1)->count()}}</td>
                            <td>{{$consignataria->contratos->where('averbador_id',$averbador->id)->where('origem',0)->count()}}</td>
                            <td>{{$consignataria->contratos->where('averbador_id',$averbador->id)->where('origem',1)->count()}}</td>


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
