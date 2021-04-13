@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <h3>Indice de monedas</h3>
                    </div>
                    <br>
                    <br>
                    <div class="d-flex justify-content-center">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">NOMBRE</th>
                                    <th scope="col">SIMBOLO</th>
                                    <th scope="col">DESCRIPCIÓN</th>
                                    <th scope="col">TASA</th>
                                    <th scope="col">LOCAL</th>
                                    <th scope="col">OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coins as $coin)
                                    <tr>
                                        <td>{!! $coin->name !!}</td>
                                        <td>{!! $coin->symbol !!}</td>
                                        <td>{!! $coin->description !!}</td>
                                        <td>{!! $coin->rate !!}</td>
                                        <td><input type="checkbox" name="tipoPropiedad[]" value="{{ $coin->local }}" {{ $coin->local == 1 ? 'checked' : ''  }} disabled></td>

                                        <td>
                                            <a type="button" class="btn btn-success" href="{{ route ('loadupdate-coins',[$coin->id]) }}"><i class="fas fa-pen"></i></a> 
                                            <a type="button" class="btn btn-danger" href="{{ route ('delete-coins',[$coin->id]) }}"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#ventanaModal">Agregar monedas</button>
                    </div>
                    <div>
                        <div class="modal fade" id="ventanaModal" tabindex="-1" role="dialog" aria-labelledby="tituloVentana" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 id="tituloVentana">Crear moneda</h5>
                                        <button class="close" data-dismiss="modal" aria-label="Cerrar">
                                            <span aria-hiden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container">
                                            <form name="fomul" method="post" action="{{route('create-coins')}}">
                                                @csrf
                                                <div class="form-group">

                                                </div>
                                                <div class="form-group">
                                                    <label for="inputNombre">Nombre corto de la moneda</label>
                                                    <br>
                                                    <select id="namecoin" name="miSelect" class="form-select" style="width:100%" onChange="imprimirValor()" required>
                                                        <option selected value="">Seleccione su moneda</option>
                                                        @foreach($names as $name)
                                                            <option value="{{$name->name}}">{{$name->name}}</option>
                                                        @endforeach
                                                        <option value="otra">Otra</option>
                                                    </select>
                                                    <div id="formOtra" ></div>
                                                    <br>
                                                <div class="form-group">
                                                    <label for="inputTasa">Tasa de la moneda</label>
                                                    <input name="tasa" type="number" step="0.00001" class="form-control" id="inputTasa" placeholder="Tasa de la moneda" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="input">Es la moneda local?</label>
                                                    <input type="checkbox" name="local" value="local" <?php foreach ($coins as $coin ) {
                                                        if ($coin->local == true) {
                                                            echo "disabled";
                                                        }
                                                    }?>>
                                                </div>
                                                <br>
                                                <div class="from-group container">
                                                    <button type="submit" class="btn btn-primary" style="width:100%">Agregar moneda</button>
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
</div>
@endsection