@extends('layouts.app')

{{-- @section('content')
<div class="container" style="max-width: 100%; overflow-x: hidden; overflow-y: auto;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Reportes Total Ingresos y Gastos</div>
                <div class="card-body">
                    <label class="form-label">Seleccione un filtro</label>
                    <br>
                    <select id="filtros" class="form-select">
                        <option selected value="0">Seleccione el filtro</option>
                        <option value="1">Entre dos fechas</option>
                        <option value="2">Último mes</option>
                        <option value="3">Último año</option>
                        <option value="4">Mes calendario</option>
                        <option value="5">Año calendario</option>
                    </select>
                    <button hidden id="borrar_filtro" type="button" class="btn btn-light">Borrar filtro</button>

                    <div hidden id="rangos">
                        <div class="form-group">
                            <label class="form-label">Desde</label>
                            <input type="date" name="fecha1" id="fecha1">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hasta</label>
                            <input type="date" name="fecha2" id="fecha2">
                        </div>
                    </div>
                    <div hidden id="mes_calend">
                        <div class="form-group">
                            <label class="form-label">Seleccione el mes a consultar</label>
                            <br>
                            <select id="meses" class="form-select">
                                <option selected>Seleccione el mes</option>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Setiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                    </div>
                    <div hidden id="año_calend">
                        <div class="form-group">
                            <label class="form-label">Seleccione el año a consultar</label>
                            <br>
                            <select id="años" class="form-select"></select>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="from-group container">
                        <button type="button" class="btn btn-primary" id="buttonfiltro"
                            style="width:100%">Consultar</button>
                    </div>
                </div>
                <div hidden id="graficos" class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Ingresos</h5>
                                <div id="donut_single_ingresos"></div>
                            </div>
                            <div id="footer_ingresos" class="card-footer text-muted">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Gastos</h5>
                                <div id="donut_single_gastos"></div>
                            </div>
                            <div id="footer_gastos" class="card-footer text-muted">
                            </div>
                        </div>
                    </div>
                </div>
                <div hidden id="cat_padres" class="row">
                    <div class="col-sm-12">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Vista Detallada</h5>
                                <div class="row justify-content-md-center">
                                    <div class="col-md-auto" id="donut_single_cate_padre"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div> --}}
@endsection