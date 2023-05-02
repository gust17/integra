@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')
    <div class="container">
        <a href="{{url('admin/user')}}" class="btn btn-warning">Voltar</a>
        <br>
        <br>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{url('admin/user/update',$user)}}" method="post">


                    @csrf
                    @method('PUT')
                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ $user->name }}" placeholder="{{ __('adminlte::adminlte.full_name') }}"
                               autofocus>

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                            </div>
                        </div>

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                        @enderror
                    </div>

                    {{-- Email field --}}
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ $user->email }}" placeholder="{{ __('adminlte::adminlte.email') }}">

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                            </div>
                        </div>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                        @enderror
                    </div>

                    {{-- Password field --}}
                    <div class="input-group mb-3">
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="{{ __('adminlte::adminlte.password') }}">

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                            </div>
                        </div>

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                        @enderror
                    </div>

                    {{-- Confirm password field --}}

                    <button type="submit"
                            class="btn btn-block bg-primary">
                        <span class="fas fa-user-plus"></span>
                        Salvar Edição
                    </button>

                </form>
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
