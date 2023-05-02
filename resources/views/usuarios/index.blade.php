@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')
    <div class="container">
        <a href="{{url('admin/user/create')}}" class="btn btn-success">Criar Usuario</a>
        <br>
        <br>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <table id="usuarios" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ação</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)

                        <tr>
                            <td>{{$user->name}}</td>
                            <td>
                                <a href="{{url('admin/user/edit',$user)}}" class="btn btn-primary">Editar</a>
                                <button class="btn btn-danger">Excluir</button>
                            </td>


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


    <script>
        $(document).ready(function () {

            $('#usuarios').DataTable();

        });
    </script>
@stop
