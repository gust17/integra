@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <table id="consignante_master" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Consignantes</th>
                        <th>Servidores Ativos</th>
                        <th>Servidores Inativos</th>


                    </tr>
                    </thead>
                    <tbody>
                    @forelse($consignanteMaster->consignantes as $consignante)

                        <tr>
                            <td><a href="{{route('consignante.show',$consignante)}}">{{$consignante->name}}</a></td>
                            <td>{{$consignante->servidors->where('ativo',1)->count()}}</td>
                            <td>{{$consignante->servidors->where('ativo',0)->count()}}</td>

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

            $('#consignante_master').DataTable();
        });
    </script>
@stop
