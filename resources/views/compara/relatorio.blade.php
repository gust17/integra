@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
    <h1>Relatório</h1>
@stop

@section('content')

    <style>
        .table {
            border-collapse: collapse;
        }

        .table td {
            height: 20px; /* Defina a altura desejada para as células da tabela */
            padding: 5px; /* Ajuste o preenchimento conforme necessário */
        }
    </style>
    <div class="card border">
        <div class="card-header">
            <h3 class="card-title">Relatório de Críticas do Arquivo Retorno</h3>
        </div>
        <div class="card-body">
            <table style="width:100%">
                <tr>
                    <th>Consignatária</th>
                    <th>Mês</th>

                </tr>
                <tr>
                    <th>Prefeitura de Macapá</th>
                    <th>MAIO/2023</th>

                </tr>


            </table>
        </div>

    </div>
    <div class="card border">
        <div class="card-body">

            <table>
                <tr>
                    <th>Linhas Descontos Enviados:</th>
                    <th>{{$totalLinhaRemessa}}</th>

                </tr>
                <tr>
                    <th>Linhas Descontos Recebidas:</th>
                    <th>{{$totalLinhaRetorno}}</th>

                </tr>


            </table>
        </div>
    </div>


    <div class="card border">
        <div class="card-body">

            <table>

                <tr>
                    <th>Total Descontos Enviados:</th>
                    <th>{{format_currency($totalRemessa)}}</th>

                </tr>
                <tr>
                    <th>Total Descontos Recebidos:</th>
                    <th>{{format_currency($totalRetorno)}}</th>

                </tr>


            </table>
        </div>
    </div>
    <div class="card border">
        <div class="card-header"><h3 class="card-title">Divergências dos Arquivos</h3></div>
        <div class="card-body">
            <h3 class="card-title">Linhas/Descontos Enviados e não retornados</h3>
            <table id="remessa" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Empresa</th>
                    <th>CPF</th>
                    <th>Matricula</th>
                    <th>Verba</th>
                    <th>Valor</th>

                </tr>
                </thead>
                <tbody>

                @forelse($remessasNaoEncontradas as $remessanaoEncontrada)

                    <tr>
                        <td>{{$remessanaoEncontrada['empresa']}}</td>
                        <td>{{$remessanaoEncontrada['cpf']}}</td>
                        <td>{{$remessanaoEncontrada['matricula']}}</td>
                        <td>{{$remessanaoEncontrada['verba']}}</td>
                        <td style="  text-align: right;">{{format_currency($remessanaoEncontrada['valor_parcela'])}}</td>

                    </tr>
                @empty

                @endforelse


                </tbody>
            </table>
            <br><br>
            <h3 class="card-title">Linhas/Descontos retornados e não Enviados</h3>
            <table id="retorno" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Empresa</th>
                    <th>CPF</th>
                    <th>Matricula</th>
                    <th>Verba</th>
                    <th>Valor</th>

                </tr>
                </thead>
                <tbody>

                @forelse($retornoNaoEncontradas as $retornonaoEncontrada)

                    <tr>
                        <td>{{$remessanaoEncontrada['empresa']}}</td>
                        <td>{{$remessanaoEncontrada['cpf']}}</td>
                        <td>{{$remessanaoEncontrada['matricula']}}</td>
                        <td>{{$remessanaoEncontrada['verba']}}</td>
                        <td style="text-align: right;">{{format_currency($remessanaoEncontrada['valor_parcela'])}}</td>

                    </tr>
                @empty

                @endforelse

                </tbody>
            </table>
            <br><br>

        </div>
    </div>
@endsection
@section('plugins.Datatables', true)
@section('plugins.Select2', true)


@section('js')
    <script> console.log('Hi!'); </script>

    <script>
        $(document).ready(function () {

            $('#remessa').DataTable({
                    "language":
                        {
                            "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
                        }
                }
            )
            ;
            $('#retorno').DataTable({
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
                    }
                }
            );

        });
    </script>
@stop
