@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 100%; overflow-x: hidden; overflow-y: auto;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Cuenta</div>
                @foreach($cuentas as $cuenta)
                <div class="card-body">
                    <div class="card">
                        <div class="card-header">Detalles de tu cuenta</div>
                        <div class="card-body">
                            <span>Nombre: {{ $cuenta->nombre }}</span>
                            <br>
                            <span>Descripcion: {{ $cuenta->descripcion }}</span>
                            <br>
                            <span>Saldo actual: {{ $cuenta->saldo }}</span>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="card">
                        <div class="card-header">Transacciones</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Id</th>
                                        <th scope="col">Id_Cuenta</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Cuenta</th>
                                        <th scope="col">Categoría</th>
                                        <th scope="col">Detalle</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">traslado</th>
                                        <th scope="col">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($transacciones)
                                    @foreach($transacciones as $transaccion)
                                    <tr>
                                        <th scope="row"></th>
                                        <td>{{ $transaccion->id }}</td>
                                        <td>{{ $transaccion->cuenta_id }}</td>
                                        <td>{{ $transaccion->tipo }}</td>
                                        <td>{{ $transaccion->created_at }}</td>
                                        <td>{{ $transaccion->nombre }}</td>
                                        <td>{{ $transaccion->descripcion }}</td>
                                        <td>{{ $transaccion->detalle }}</td>
                                        @if($transaccion->tipo == 'Gasto')
                                        <td>-{{ $transaccion->monto }}</td>
                                        @else
                                        <td>{{ $transaccion->monto }}</td>
                                        @endif
                                        <td> {{ $transaccion->traslado }}</td>
                                        <td>
                                            <a href="/editar/{{ $transaccion->id }}/{{ $transaccion->cuenta_id }}/{{ $transaccion->categoria_id }}" role="button" class="btn btn-success"><i class="fas fa-pen"></i></a> 
                                            <a href="/eliminartransacc/{{ $transaccion->id }}/{{ $transaccion->cuenta_id }}" role="button" class="btn btn-danger"><i class="fas fa-trash"></i></a> 
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#Modal3">Crear
                                </button>
                            </div>
                            <div class="modal fade" id="Modal3" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModal3" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Crear transación</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="/store">
                                            @csrf
                                                <div class="form-group">
                                                    <label for="inputNombre">Seleccione el tipo</label>
                                                    <br>
                                                    <select id="tipo" name="tipo" class="form-select"
                                                        style="width:100%" required>
                                                        <option selected value="">Seleccione el tipo</option>
                                                        <option value="Ingreso">Ingreso</option>
                                                        <option value="Gasto">Gasto</option>
                                                        <option value="Traslado">Traslado</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label id="cuenta" for="inputCuenta1">Cuenta</label>
                                                    <input name="cuenta1" type="hidden" class="form-control"
                                                        id="inputCuenta1" value="{{ $cuenta->id }}">
                                                    <input disabled name="cuenta" type="text" class="form-control"
                                                        id="inputCuenta" placeholder="{{ $cuenta->nombre }}">
                                                </div>
                                                <div id="txt" class="form-group">
                                                    <label hidden id="destino" for="cuentas">Cuenta de destino</label>
                                                    <select hidden style="width:100%" name="cuentas" class="form-select"
                                                        id="cuentas"></select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputNombre">Nombre de la categoría</label>
                                                    <br>
                                                    <select id="categoria" name="selectCategoria" class="form-select"
                                                        style="width:100%" required>
                                                        <option selected value="">Seleccione la categoría</option>
                                                        @if($categorias)
                                                        @foreach($categorias as $categoria)
                                                        <option value="{{ $categoria->id }}">{{ $categoria->descripcion }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputDetalle">Detalle de la transacción</label>
                                                    <input name="detalle" type="text" class="form-control"
                                                        id="inputDetalle" placeholder="Detalle de la transacción" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputMonto">Monto de la transacción</label>
                                                    <input name="monto" type="numeric" class="form-control"
                                                        id="inputMonto" placeholder="Monto de la transacción" required>
                                                </div>
                                                <div class="from-group container">
                                                    <button type="submit" class="btn btn-primary"
                                                        style="width:100%">Crear</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="card">
                        <div class="card-header">Categorías</div>
                        <div class="card-body">
                            @if($progress_bar)
                            @foreach($progress_bar as $progress)
                                <div class="card">
                                    <div class="card-header">{{ $progress->descripcion }}</div>
                                        <div class="card-body">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress->resultado }}%">{{ $progress->resultado }}%</div>
                                            </div>
                                        </div>
                                </div>
                                <br>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection