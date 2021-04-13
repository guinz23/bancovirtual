@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card  d-flex align-content-center">
                <div class="card-header" style="height: 85vh">
                    <div style="height:17vh"></div>
                    <div class="d-flex justify-content-center">
                        <h3>Editar moneda</h3>
                    </div>
                    <br>
                    <br>
                    <div class="container d-flex justify-content-center">
                        <form name="fomul" method="post" action="{{ route ('update-coins') }}">
                            @csrf
                            <div class="form-group">
                                <label for="inputNombre">Nombre corto de la moneda</label>
                                <br>
                                <select id="namecoin" name="miSelect" class="form-select" style="width:100%" onChange="imprimirValor()" required>
                                    @foreach($names as $name)
                                        @if($name->name == $coin->name)
                                        <option selected value="{{$name->name}}">{{$name->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <br>
                            <div class="form-group">
                                <label for="inputTasa">Tasa de la moneda</label>
                                <input name="tasa" type="number" step="0.00001" class="form-control" id="inputTasa" placeholder="Tasa de la moneda" value="<?php echo $userco->rate?>">
                            </div>
                            <div class="form-group" style="display:none">
                                <label for="id">id de la moneda</label>
                                <input name="id" type="numeric" class="form-control" id="id" placeholder="Id de la moneda" value="<?php echo $userco->id?>">
                            </div>
                            <div class="form-group">
                                <label for="input">Es la moneda local?</label>
                                <input type="checkbox" name="local" value="local" <?php 
                                $haslo=false;
                                foreach ($coins as $co) {
                                    if ($co->local == true) {
                                        $haslo=true;
                                    }
                                }
                                if ($userco->local == true && $haslo == true) {
                                    echo "checked";
                                }else if($userco->local != true && $haslo == true){
                                    echo "disabled";
                                }?>>
                            </div>
                            <br>
                            <div class="from-group container">
                                <button type="submit" class="btn btn-primary" style="width:100%">Actualizar moneda</button>
                            </div>
                        </form>
                    </div>
                    <div style="height:17vh"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection