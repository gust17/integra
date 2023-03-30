@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<div class="col-md-6"><h1>Dashboard</h1></div>
<div class="col-md-6"></div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="container">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Não iniciadas</h4>
                    </div>
                    <div class="card-body">
                        <table id="consignataria" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Consignataria</th>


                            </tr>
                            </thead>
                            <tbody>
                            @forelse($naovalidadas as $naovalidada)

                                <tr>
                                    <td>{{$naovalidada->name}}</td>


                                </tr>
                            @empty
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('plugins.Datatables', true)
@section('js')
    <script>
        $(document).ready(function () {

            $('#consignataria').DataTable();
            $('#bancos').select2();
        });
    </script>
@stop
