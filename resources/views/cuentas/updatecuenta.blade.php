@extends('layouts.app')

@section('content')
<div class="container">
    <form method="POST" action="/cuenta/{{ $cuenta->id }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="inputName">Nombre de cuenta</label>
            <input type="text" class="form-control" name="nombre" id="inputName" aria-describedby="nameHelp"
                placeholder="Ingrese el nombre de la cuenta" value="{{ $cuenta->nombre }}" required>
        </div>
        <div class="form-group">
            <label for="inputDescripcion">Descripción</label>
            <input type="text" class="form-control" name="descripcion" id="inputDescripcion"
                placeholder="Escriba una breve descripción de su cuenta" value="{{ $cuenta->descripcion }}" required>
        </div>
        <div class="form-group">
            <label for="inputSaldo">Saldo inicial</label>
            <input type="numeric" class="form-control" name="saldo" id="inputSaldo" placeholder="Saldo inicial"
                value="{{ $cuenta->saldo }}" required>
        </div>
        <br>
        <div class="from-group container">
            <button type="submit" class="btn btn-primary">Actualizar cuenta</button>
            <a href="/home" class="btn btn-danger" role="button" data-dismiss="modal">Cancelar</a>
        </div>
    </form>
</div>
@endsection
