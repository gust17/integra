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
                                <form action="{{ route('user.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger delete-user" data-name="{{ $user->name }}"
                                            type="submit">Delete
                                    </button>
                                </form>
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
    <link rel="stylesheet" href="sweetalert2.min.css">
@stop
@section('plugins.Datatables', true)
@section('plugins.Select2', true)


@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $(document).ready(function () {

            $('#usuarios').DataTable();

        });
    </script>
    <script>
        $(document).ready(function () {
            $('.delete-user').click(function (event) {
                event.preventDefault();
                const form = event.target.form;
                const name = $(event.target).data('name');
                Swal.fire({
                    title: 'Deseja continuar?',
                    text: "Você não será capaz de reverter isso!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, delete!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    </script>
@stop
