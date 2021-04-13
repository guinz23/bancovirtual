@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ Auth::user()->name }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <h3>Categorías</h3>

                    <div class="container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="display: none;" scope="col">Id</th>
                                    <th style="display: none;" scope="col">Usuario</th>
                                    <th style="display: none;" scope="col">id Padre</th>
                                    <th scope="col">Categoria padre</th>
                                    <th scope="col">tipo</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Presupuesto</th>
                                    <th scope="col">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($categorias_all) 
                                @foreach($categorias_all as $categoria)
                                <tr>
                                    <td style="display: none;" >{{ $categoria->id }}</td>
                                    <td style="display: none;" >{{ $categoria->user_id }}</td>
                                    @if($categoria->categoria_id)
                                    <td style="display: none;">{{ $categoria->categoria_id }}</td>
                                    @else
                                    <td style="display: none;"></td>
                                    @endif
                                
                                    @if($categoria->hijo)
                                    <td>{{ $categoria->hijo }}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    <td>{{ $categoria->tipo }}</td>
                                    <td>{{ $categoria->padre }}</td>
                                    <td>{{ $categoria->presupuesto }}</td>
                                    <td>
                                        <a href="/actualizarcategorias/{{ $categoria->id }}" role="button" class="btn btn-success"><i class="fas fa-pen"></i></a> 
                                        <a href="/eliminarcategoria/{{ $categoria->id }}" role="button" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!--  Botón de ventana modal y modal -->

                    <button class="btn btn-primary" data-toggle="modal" data-target="#ventanaModal">Agregar una categoría</button>

                    <div class="modal fade" id="ventanaModal" tabindex="-1" role="dialog" aria-labelledby="tituloVentana" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 id="tituloVentana">Categoría</h5>
                                    <button class="close" data-dismiss="modal" aria-label="Cerrar">
                                        <span aria-hiden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <form method="POST" action="/crearcategoria">
                                        @csrf
                                        <div class="form-group">
                                                <label for="inputtipo">Tipo</label>
                                                <br>
                                                <select name="tipo" class="form-select" style="width:100%">
                                                    <option>Ingresos</option>
                                                    <option>Gastos</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPadre">Categoría Padre</label>
                                                <br>
                                                <select name="padre" class="form-select" style="width:100%">
                                                @if($categorias)
                                                    <option value="" selected>N/a</option>
                                                @foreach($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}">{{ $categoria->descripcion }}</option>
                                                @endforeach
                                                @endif
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputCoin">Monedas</label>
                                                <br>
                                                <select name="coin" class="form-select" style="width:100%" required>
                                                    <option value="" selected>Seleccione una moneda</option>
                                                @foreach($coins as $coin)
                                                    <option value="{{ $coin->id }}">{{ $coin->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputDescripcion">Descripción</label>
                                                <input type="text" class="form-control" name="descripcion" id="inputDescripcion" placeholder="Escriba una breve descripción de su categoría" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="inputPresupuesto">Presupuesto</label>
                                                <input type="numeric" class="form-control" name="presupuesto" id="inputPresupuesto" placeholder="Presupuesto" required>
                                            </div>
                                            <br>
                                            <div class="from-group container">
                                                <button type="submit" class="btn btn-primary" style="width:100%">Agregar categoría</button>
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