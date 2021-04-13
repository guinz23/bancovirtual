@extends('layouts.app')

@section('content')
<div class="container">
    <div class="container">
        <form method="POST" action="/categoria/{{ $seleccionada->id }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="inputtipo">Tipo</label>
                <br>
                <select name="tipo" class="form-select" style="width:100%">
                    @if($seleccionada->tipo == "Ingresos")
                        <option selected>Ingresos</option>
                        <option>Gastos</option>
                    @else
                        <option>Ingresos</option>
                        <option selected>Gastos</option>
                    @endif
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
                <label for="inputCoin">Moneda</label>
                <br>
                <select name="coin" class="form-select" style="width:100%">
                    <option value="" selected>Seleccione una moneda</option>
                    @foreach($coins as $coin)
                    <option value="{{ $coin->id }}">{{ $coin->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="inputDescripcion">Descripción</label>
                <input type="text" class="form-control" name="descripcion" id="inputDescripcion"
                    placeholder="Escriba una breve descripción de su categoría" value="{{ $seleccionada->descripcion }}" required>
            </div>

            <div class="form-group">
                <label for="inputPresupuesto">Presupuesto</label>
                <input type="numeric" class="form-control" name="presupuesto" id="inputPresupuesto"
                    placeholder="Presupuesto" value="{{ $seleccionada->presupuesto }}" required>
            </div>
            <br>
            <div class="from-group container">
                <button type="submit" class="btn btn-primary" style="width:100%">Actualizar categoría</button>
            </div>
        </form>
    </div>
</div>
@endsection