@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{route('consignante-master.store')}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="">Nome do Consignante Master:</label>
                        <input class="form-control" type="text" name="name">
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
                        <th>Consignantes Master</th>


                    </tr>
                    </thead>
                    <tbody>
                    @forelse($consignante_masters as $master)

                        <tr>
                            <td>{{$master->name}}</td>
                            <td></td>
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
