@extends('adminlte::page')

@section('title', 'Consignatarias')

@section('content_header')
    <h1>{{$consignataria->name}}</h1>
@stop

@section('content')
    <div class="modal fade" id="myModal">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Dados dos Contratos</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">


                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Servidor</th>
                            <th>Matricula</th>
                            <th>Valor descontado</th>
                            <th>Prazo Total</th>
                            <th>Parcela Atual</th>
                            <th>Contrato</th>
                            <th>Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tr>
                            <td class="servidor1"></td>
                            <td class="matricula1"></td>
                            <td class="valor_desconto1"></td>
                            <td class="prazo_total1"></td>
                            <td class="parcela_atual1"></td>
                            <td class="contrato1"></td>
                            <td>
                                <button class="btn btn-success">Transferir</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="servidor"></td>
                            <td class="matricula"></td>
                            <td class="valor_desconto"></td>
                            <td class="prazo_total"></td>
                            <td class="parcela_atual"></td>
                            <td class="contrato"></td>
                            <td>
                                <button class="btn btn-success">Transferir</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table id="tipos" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Tipo de Status</th>
                        <th>Quantidade</th>
                        <th>Ações</th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Validados</td>
                        <td>{{$contratos->where('status',1)->count()}}</td>
                        <td>
                            <a href="{{route('consignataria.validada',$consignataria->id)}}" class="btn btn-primary">Visualizar</a>
                        </td>

                    </tr>
                    <tr>
                        <td>Não Validados</td>
                        <td>{{$contratos->where('status','!=',1)->whereNull('obs')->whereNull('contrato_id')->count()}}</td>
                        <td>
                            <a href="{{route('consignataria.naovalidada',$consignataria->id)}}" class="btn btn-primary">Visualizar</a>
                        </td>

                    </tr>
                    <tr>
                        <td>Contratos com  Inexistentes/Divergentes</td>
                        <td>{{$contratosSemServidor->count()}}</td>
                        <td>
                            <a href="{{route('consignataria.sem_servidor',$consignataria->id)}}" class="btn btn-primary">Visualizar</a>
                        </td>

                    </tr>
                    <tr>
                        <td>Contratos sem Pessoa</td>
                        <td>{{$contratosSemPessoa->count()}}</td>
                        <td>
                            <a href="{{route('consignataria.sem_pessoa',$consignataria->id)}}" class="btn btn-primary">Visualizar</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Possiveis Renegociações/Novos Contratos</td>
                        <td>{{$possivelRenegociacao->count()}}</td>
                        <td>
                            <a href="{{route('consignataria.novo-contrato',$consignataria->id)}}" class="btn btn-primary">Visualizar</a>
                        </td>
                    </tr>

                    <tr>
                        <td>Contratos Parecidos/Semelhantes</td>
                        <td>{{$contratos_semelhantes->count()}}</td>
                        <td>
                            <a href="{{route('consignataria.semelhante',$consignataria->id)}}" class="btn btn-primary">Visualizar</a>
                        </td>
                    </tr>

                    <tr>
                        <td>Contratos não Encontrados no Banco</td>
                        <td>{{$contratos->where('origem',0)->where('status','!=',1)->whereNull('obs')->count()}}</td>
                        <td>
                            <a href="{{route('consignataria.sem_banco',$consignataria->id)}}" class="btn btn-primary">Visualizar</a>
                        </td>

                    </tr>
                    <tr>
                        <td>Contratos não Encontrados na Prefeitura</td>
                        <td>{{$contratos->where('origem',1)->where('status','!=',1)->count()}}</td>
                        <td>
                            <a href="{{route('consignataria.sem_prefeitura',$consignataria->id)}}" class="btn btn-primary">Visualizar</a>
                        </td>

                    </tr>
                    <tr>
                        <td>Contratos com Alguma Observação</td>
                        <td>{{$contratos->whereNotNull('obs')->count()}}</td>
                        <td>
                            <a href="{{route('consignataria.obs',$consignataria->id)}}" class="btn btn-primary">Visualizar</a>
                        </td>

                    </tr>

                    <tr>
                        <td>Total de contratos do arquivo</td>
                        <td>{{$contratos->count()}}</td>
<td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="contratos" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Servidor</th>
                            <th>Matricula</th>
                            <th>Valor Descontado</th>
                            <th>Prazo Total</th>
                            <th>Prestação Atual</th>
                            <th>Contrato</th>
<th>Origem</th>
                            <th>Erros</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($contratos as $contrato)

                            <tr @if($contrato->contrato == 0 ) style="background-color: #dc4c3d" @endif

                            @if($contrato->status == 2 ) style="background-color: #ffdf7e" @endif>
                                <td>{{$contrato->servidor->pessoa->name}}</td>
                                <td>{{$contrato->servidor->matricula}}</td>
                                <td>{{format_currency($contrato->valor_parcela)}}</td>
                                <td>{{$contrato->total_parcela}}</td>
                                <td>{{$contrato->n_parcela_referencia}}</td>
                                <td>{{$contrato->contrato}}</td>
                                <td>{{$contrato->origem}}</td>

                                <td>

                                    @if($contrato->status != 1 )
                                        <button class="btnAbrirModal" data-id="{{$contrato->id}}">Consultar</button><br>

                                    @endif
                                    @if($contrato->contrato == 0 )

                                        Contrato não encontrado no Banco<br>
                                    @endif
                                    @if($contrato->status == 0 )

                                        Não validado <br>
                                    @endif
                                    @if($contrato->status == 2 )
                                        Contrato nao encontrado na Prefeitura <br>
                                    @endif
                                    @if($contrato->servidor->ativo == 0 )
                                        Servidor não esta no arquivo Prefeitura <br>
                                    @endif
                                    @if($contrato->servidor->pessoa->ativo == 0 )
                                        Pessoa não esta no arquivo Prefeitura<br>
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

            $('#contratos').DataTable();


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
