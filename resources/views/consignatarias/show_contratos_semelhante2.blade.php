@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
    <h1>{{ $consignataria->name }} - Contratos {{ $title }}</h1>
@stop

@section('content')

    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Filtros
                </h3>
            </div>
            <div class="card-body">
                <form action="{{route('consignataria.semelhante.pesquisa')}}" method="post">
                    @csrf
                    <input type="hidden" name="consignataria" value="{{$consignataria->id}}">
                    <input type="hidden" name="averbador" value="{{$averbador->id}}">
                    <div class="row">
                        <div class="form-group col">
                            <label for="">Matricula</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="matricula_inexistente" value="1">
                                <label class="form-check-label">Inexistente</label>
                            </div>
                        </div>
                        <div class="form-group col">
                            <label for="">Matricula </label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="matricula" value="1">
                                <label class="form-check-label">Diferente</label>
                            </div>
                        </div>
                        <div class="form-group col">
                            <label for="">Desconto </label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="desconto" value="1">
                                <label class="form-check-label">Diferente</label>
                            </div>
                        </div>
                        <div class="form-group col">
                            <label for="">Parcela </label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="parcela" value="1">
                                <label class="form-check-label">Diferente</label>
                            </div>
                        </div>
                        <div class="form-group col">
                            <label for="">Prazo </label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="prazo" value="1">
                                <label class="form-check-label">Diferente</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-block btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="container-fluid">
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
                            <th>Servidor Semelhante</th>
                            <th>Matricula Banco</th>
                            <th>Matricula Consignante</th>
                            <th>Valor descontado Banco</th>
                            <th>Valor descontado Consignante</th>
                            <th>Prazo Total Banco</th>
                            <th>Prazo Total Consignante</th>
                            <th>Prestação Atual Banco</th>
                            <th>Prestação Atual Consignante</th>
                            <th>Contrato</th>
                            <th>Contrato</th>
                            <th>Origem</th>
                            <th>Diferenças</th>


                        </tr>
                        </thead>
                        <tbody>

                        @forelse($contratos->where('origem',0) as $contrato)

                            <tr>

                                <td>{{ $contrato->servidor->pessoa->name }}</td>

                                <td>{{ $contrato->semelhante->servidor->pessoa->name }}</td>
                                <td>{{ $contrato->servidor->matricula }}</td>
                                <td>{{ $contrato->semelhante->servidor->matricula }}</td>
                                <td>{{ $contrato->valor_parcela }}</td>
                                <td>{{ $contrato->semelhante->valor_parcela}}</td>
                                <td>{{ $contrato->total_parcela }}</td>
                                <td>{{ $contrato->semelhante->total_parcela }}</td>
                                <td>{{ $contrato->n_parcela_referencia }}</td>
                                <td>{{ $contrato->semelhante->n_parcela_referencia }}</td>
                                <td>{{ $contrato->contrato }}</td>
                                <td>{{ $contrato->semelhante->contrato }}</td>
                                <td>{{$contrato->origem}}</td>
                                <td>
                                    @if ($contrato->servidor->pessoa->id != $contrato->semelhante->servidor->pessoa->id)
                                        Matricula
                                    @endif
                                    @if ($contrato->valor_parcela != $contrato->semelhante->valor_parcela)
                                        ,Desconto
                                    @endif
                                    @if ($contrato->n_parcela_referencia != $contrato->semelhante->n_parcela_referencia)
                                        ,Parcela
                                    @endif
                                    @if ($contrato->total_parcela != $contrato->semelhante->total_parcela)
                                        ,Prazo
                                    @endif
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

    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Geral</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="banco" class="table table-striped">
                        <thead>
                        <tr>
                            <th>cd_solicitacao</th>
                            <th>NR_CTR_CVN</th>
                            <th>COD_CPF_CGC</th>
                            <th>NOM</th>
                            <th>NR_CTR_OPR</th>
                            <th>DT_OPR_CDC</th>
                            <th>VCTO_PRIM_PARCELA</th>
                            <th>VL_INC_PCL_CTR</th>
                            <th>QT_PCL_CTR</th>
                            <th>DT_FIM_CTR_CDC</th>
                            <th>NR_PRAZO_MES</th>
                            <th>NR_PARCELA_MES_ATUAL</th>


                        </tr>
                        </thead>
                        <tbody>
                        @forelse($contratos->where('origem',1) as $contrato)

                            <tr>

                                <td>{!!  json_decode($contrato->semelhante->linha_geral)->cd_solicitacao !!}</td>
                                <td>{!!  json_decode($contrato->linha_geral)->NR_CTR_CVN !!}</td>
                                <td>{!!  json_decode($contrato->linha_geral)->COD_CPF_CGC !!}</td>
                                <td>{!!  json_decode($contrato->linha_geral)->NOM !!}</td>
                                <td>{!!  json_decode($contrato->linha_geral)->NR_CTR_OPR !!}</td>
                                <td>{!!  validanovadata(json_decode($contrato->linha_geral)->DT_OPR_CDC) !!}</td>
                                <td>{!!  validanovadata(json_decode($contrato->linha_geral)->VCTO_PRIM_PARCELA) !!}</td>
                                <td>{!!  json_decode($contrato->linha_geral)->VL_INC_PCL_CTR !!}</td>
                                <td>{!!  json_decode($contrato->linha_geral)->QT_PCL_CTR !!}</td>
                                <td>{!!  validanovadata(json_decode($contrato->linha_geral)->DT_FIM_CTR_CDC)!!}</td>
                                <td>{!! validanovadataQTD(json_decode($contrato->linha_geral)->VCTO_PRIM_PARCELA) !!}</td>
                                <td>@if ($contrato->total_parcela != $contrato->semelhante->total_parcela)
                                        Prazo
                                    @endif</td>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
@stop
@section('plugins.Datatables', true)
@section('plugins.Select2', true)


@section('js')
    <script>
        console.log('Hi!');
    </script>

    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function () {

            $('#prefeitura').DataTable({

                dom: 'Bfrtip',
                buttons: [{
                    extend: 'csv',
                    text: 'Exportar para CSV'
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

            });
            $('#banco').DataTable({

                dom: 'Bfrtip',
                buttons: [{
                    extend: 'csv',
                    text: 'Exportar para CSV',
                    charset: "utf8",
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

            });
            $('#banco-separado').DataTable({

                dom: 'Bfrtip',
                buttons: [{
                    extend: 'csv',
                    text: 'Exportar para CSV',
                    charset: "utf8",
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

            });


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
