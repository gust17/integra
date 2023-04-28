@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
   <h1>Geral</h1>
@stop

@section('content')

    <div class="container-fluid">
        <div class="rol">

            <br>
            <br>

        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Prefeitura</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="prefeitura" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Servidor</th>
                            <th>Matricula</th>
                            <th>Valor Descontado</th>
                            <th>Prazo Total</th>
                            <th>Prestação Atual</th>
                            <th>Contrato</th>
                            <th>Origem</th>
                            <th>OBS</th>


                        </tr>
                        </thead>
                        <tbody>

                        @forelse($contratos as $contrato)

                            <tr>
                                <td>{{$contrato->servidor->pessoa->name}}</td>
                                <td>{{$contrato->servidor->matricula}}</td>
                                <td>{{format_currency($contrato->valor_parcela)}}</td>
                                <td>{{$contrato->total_parcela}}</td>
                                <td>{{$contrato->n_parcela_referencia}}</td>
                                <td>{{$contrato->contrato}}</td>
                                <td>{{$contrato->getNovaOrigem()}}</td>
                                <td>

                                </td>


                            </tr>
                        @empty
                        @endforelse

                        </tbody>
                    </table>
                </div>
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

    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function () {

            $('#prefeitura').DataTable(
                {

                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'csv',
                        text: 'Exportar para CSV',
                        charset: "utf8"
                    },
                        {
                            extend: 'excel',
                            text: 'Exportar para Excel'
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            text: 'Exportar para PDF'
                        },
                        'print',

                    ],

                }
            );
            $('#banco').DataTable(
                {

                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'csv',
                        text: 'Exportar para CSV',
                        charset: "utf8"
                    },
                        {
                            extend: 'excel',
                            text: 'Exportar para Excel'
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            text: 'Exportar para PDF'
                        },
                        'print',

                    ],

                }
            );


            $('#bancos').select2();
        });
    </script>

    <script>
        $('.btnAbrirModal').click(function () {
            var id = $(this).data('id');
            $.ajax({

                type: 'GET',
                url: '/ajax-modal/' + id,
                success: function (data) {
                    console.log(data)
                    if (data && data !== '') {
                        if (data[0] == null) {
                            alert('Não há dados a serem exibidos.');
                        }
                        $('#modal-content').html(data);
                        $('.servidor').html(data[0].servidor.pessoa.name)
                        $('.matricula').html(data[0].servidor.matricula)
                        $('.valor_desconto').html(data[0].valor_parcela)
                        $('.prazo_total').html(data[0].total_parcela)
                        $('.contrato').html(data[0].contrato)
                        $('.parcela_atual').html(data[0].n_parcela_referencia)
                        $('.servidor1').html(data[1].servidor.pessoa.name)
                        $('.matricula1').html(data[1].servidor.matricula)
                        $('.valor_desconto1').html(data[1].valor_parcela)
                        $('.prazo_total1').html(data[1].total_parcela)
                        $('.contrato1').html(data[1].contrato)
                        $('.parcela_atual1').html(data[1].n_parcela_referencia)
                        $('#myModal').modal('show');
                    } else {
                        alert('Não há dados a serem exibidos.');
                    }
                }
            });
        });


    </script>
@stop
