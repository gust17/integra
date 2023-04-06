@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{route('consignante.store')}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="">Nome do Consignante:</label>
                        <input class="form-control" type="text" name="name">
                    </div>
                    <div class="form-group">
                        <label for="">Consignante-Master</label>
                        <select class="form-control" name="consignante_master_id" id="">

                            @forelse($consignantes_masters as $consignantes_master)
                                <option value="{{$consignantes_master->id}}">{{$consignantes_master->name}}</option>

                            @empty
                            @endforelse
                        </select>
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
                        <th>Consignantes</th>
                        <th>Consignantes Master</th>


                    </tr>
                    </thead>
                    <tbody>
                    @forelse($consignantes as $consignante)

                        <tr>
                            <td>{{$consignante->name}}</td>
                            <td>{{$consignante->consignante_master->name}}</td>

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
