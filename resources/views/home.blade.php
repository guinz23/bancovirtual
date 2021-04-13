@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Bienvenido {{ Auth::user()->name }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!--div for the user information-->
                    <br>
                    <div style="display: flex; flex-direction: row">
                        <div style="width: 20%; heigth: 10vh">
                            @auth
                                <img src="{{auth()->user()->user_avatar()}}" width="150vw" style="padding-left: 10%">
                            @endauth
                        </div>
                        <div style="width: 60%; heigth: 10vh">
                        <br>
                        <br>
                        <p>Usuario: {{ Auth::user()->name }}</p>
                        <p>Email: {{ Auth::user()->email }} </p>
                        </div>
                    </div>
                    
                    <!--div for the accounts-->
                    <br>
                    <br>
                    <br>
                    
                    <h3>Cuentas</h3>
                        
                    <div class="container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th style="display: none;" scope="col">Id</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Moneda</th>
                                    <th scope="col">Saldo Actual</th>
                                    <th scope="col">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($cuentas) 
                                @foreach($cuentas as $cuenta)
                                <tr>
                                    <th scope="row"><img src="\img\credit-card.png" width="30vw" style="padding-left: 10%"></th>
                                    <td style="display: none;">{{ $cuenta->id }}</td>
                                    <td><a href="/transacciones/{{ $cuenta->id }}">{{ $cuenta->nombre }}</a></td>
                                    <td>{{ $cuenta->descripcion }}</td>
                                    <td>{{ $cuenta->moneda }}</td>
                                    <td>{{ $cuenta->saldo }}</td>
                                    <td>
                                        <a href="/editarcuenta/{{ $cuenta->id }}" role="button" class="btn btn-success"><i class="fas fa-pen"></i></a> 
                                        <a href="/eliminarcuenta/{{ $cuenta->id }}" role="button" class="btn btn-danger"><i class="fas fa-trash"></i></a> 
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!--  Botón de ventana modal y modal -->
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#ventanaModal">Agregar una cuenta</button>
                    </div>

                    <div class="modal fade" id="ventanaModal" tabindex="-1" role="dialog" aria-labelledby="tituloVentana" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 id="tituloVentana">Cuenta</h5>
                                    <button class="close" data-dismiss="modal" aria-label="Cerrar">
                                        <span aria-hiden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <form method="POST" action="/crearcuenta">
                                        @csrf
                                            <div class="form-group">
                                                <label for="inputName">Nombre de cuenta</label>
                                                <input type="text" class="form-control" name="nombre" id="inputName" aria-describedby="nameHelp" placeholder="Ingrese el nombre de la cuenta" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputDescripcion">Descripción</label>
                                                <input type="text" class="form-control" name="descripcion" id="inputDescripcion" placeholder="Escriba una breve descripción de su cuenta" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputMoneda">Moneda</label>
                                                <br>
                                                <select name="monedas" class="form-select" style="width:100%">
                                                    <option selected>Seleccione su moneda</option>
                                                    @if($monedas)
                                                    @foreach($monedas as $moneda)
                                                    <option value="{{ $moneda->id }}">{{ $moneda->name }} ({{ $moneda->symbol }})</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputSaldo">Saldo inicial</label>
                                                <input type="numeric" class="form-control" name="saldo" id="inputSaldo" placeholder="Saldo inicial" required>
                                            </div>
                                            <br>
                                            <div class="from-group container">
                                                <button type="submit" class="btn btn-primary" style="width:100%">Crear cuenta</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" type="button" data-dismiss="modal">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection