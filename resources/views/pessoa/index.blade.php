@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Pesssoas</h1>
@stop

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{url('datalhes')}}">
                    <div class="form-group">
                        <label for="">Pesquisar Nome</label>
                        <select class="form-control" name="pessoa" id="pessoa"></select>
                    </div>
                    <div class="form-group">
                        <button>Pesquisar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="pessoas" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Pessoa</th>
                        <th>CPF</th>
                        <th>QTD MATRICULA</th>

                    </tr>
                    </thead>
                    <tbody>
                    @forelse($pessoas as $pessoa)

                        <tr>
                            <td>{{$pessoa->name}}</td>
                            <td>{{$pessoa->cpf}}</td>
                            <td>{{$pessoa->servidors_count}}</td>
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

            $('#pessoas').DataTable();
        });


    </script>
    <script>
        $(document).ready(function () {
            $('#pessoa').select2({
                minimumInputLength: 3,
                ajax: {
                    url: '/api/pessquisapessoa',
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: (params) => {
                        const term = params.term.trim();
                        const isCPF = /^\d{11}$/.test(term);
                        return {[isCPF ? 'cpf' : 'q']: term};
                    },
                    processResults: (data) => ({
                        results: data.map(({id, name, servidors_count}) => ({
                            id,
                            text: `${name} - ${servidors_count} matricula(s)`,
                        })),
                    }),
                },
            });
        });
    </script>
@stop
