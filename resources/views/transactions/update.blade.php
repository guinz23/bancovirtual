@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 100%; overflow-x: hidden; overflow-y: auto;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Actualizar Transacción</div>
                <div class="card-body">
                    <form method="POST" action="/actualizartransacc/{{$transaccion->id}}">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="inputNombre">Seleccione el tipo</label>
                        <br>
                        <select id="tipotransacc" name="tipo" class="form-select" style="width:100%">
                            @if($transaccion->traslado)
                            <option selected value="Traslado">Traslado</option>
                            @endif
                            @if($transaccion->tipo == "Ingreso" && !$transaccion->traslado)
                            <option selected value="Ingreso">Ingreso</option>
                            @endif
                            @if($transaccion->tipo == "Gasto" && !$transaccion->traslado)
                            <option selected value="Gasto">Gasto</option>
                            @endif
                            <option value="Ingreso">Ingreso</option>
                            <option value="Gasto">Gasto</option>
                            <option value="Traslado">Traslado</option>
                        </select>
                    </div>
                        @foreach($cuentas as $cuenta)
                        <div class="form-group">
                            <label id="cuenta" for="inputCuenta1">Cuenta</label>
                            <input name="cuenta1" type="hidden" class="form-control" id="inputCuenta1"
                                value="{{ $cuenta->id }}">
                            <input disabled name="cuenta" type="text" class="form-control" id="inputCuenta"
                                placeholder="{{ $cuenta->nombre }}">
                        </div>
                        @endforeach
                        <div id="txt" class="form-group">
                            <label hidden id="destino" for="cuentas">Cuenta de destino</label>
                            <select hidden style="width:100%" name="cuentas" class="form-select" id="cuentas">
                                @if($destinos)
                                @foreach($destinos as $destino)
                                    <option value="{{ $destino->id }}">{{  $destino->nombre }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputNombre">Nombre de la categoría</label>
                            <br>
                            <select id="categoria" name="selectCategoria" class="form-select" style="width:100%"
                                required>
                                @if($categorias)
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->descripcion }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputDetalle">Detalle de la transacción</label>
                            <input name="detalle" type="text" class="form-control" id="inputDetalle"
                                placeholder="Detalle de la transacción" value="{{ $transaccion->detalle }}" required>
                        </div>
                        <div class="form-group">
                            <label for="inputMonto">Monto de la transacción</label>
                            <input name="monto" type="numeric" class="form-control" id="inputMonto"
                                placeholder="Monto de la transacción" value="{{ $transaccion->monto }}" required>
                        </div>
                        <div class="from-group container">
                            <button type="submit" class="btn btn-primary" style="width:100%">Actualizar</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection