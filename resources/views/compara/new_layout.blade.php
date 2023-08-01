@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
    <h1>Geral</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Insirar os arquivos</h3>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">

                @csrf
                <div class="form-group">
                    <label for="">Remessa</label>
                    <input type="file" name="remessa" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Retorno</label>
                    <input type="file" name="retorno" class="form-control">
                </div>
                <button class="btn btn-success">Salvar</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Insirar os arquivos</h3>
        </div>
        <div class="card-body">
            <div id="agora" class="form-group">
                <label for="">Campos</label>
                <div class="row">
                    <div class="col-md-5">
                        <input class="form-control" type="text" name="campo[]" id="">
                    </div>
                    <div class="col-md-5">
                        <input class="form-control" type="text" name="campo[]" id="">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" id="add-row"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $("#add-row").click(function () {
                var newRow = `
                <div class="row">
                    <div class="col-md-5">
                        <input class="form-control" type="text" name="campo[]" id="">
                    </div>
                    <div class="col-md-5">
                        <input class="form-control" type="text" name="campo[]" id="">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary remove-row"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
            `;
                $("#agora").append(newRow);
            });

            $(document).on("click", ".remove-row", function () {
                $(this).parent().parent().remove();
            });
        });
    </script>
@endsection
