$(document).ready(function () {
    mostrarElementos();
    $('#buttonfiltro').click(function () {
        $('#graficos').attr('hidden', false);
        generar_reporte();
    });
    $('#borrar_filtro').click(function () {

        var filtros = document.getElementById('filtros');
        filtros.value = 0;

        $('#rangos').attr('hidden', true);
        $('#mes_calend').attr('hidden', true);
        $('#año_calend').attr('hidden', true);
        $('#cat_padres').attr('hidden', true);
        $('#graficos').attr('hidden', true);
        $('#ingresos').remove();
        $('#gastos').remove();

    });
});

var temp;
var hijos = [];
var seleccion = "";

function generar_reporte() {
    var combo = document.getElementById("filtros");
    switch (parseInt(combo.options[combo.selectedIndex].value)) {
        case 1:
            var fecha1 = document.getElementById('fecha1').value;
            var fecha2 = document.getElementById('fecha2').value;
            ajax('/transacciones', 'POST', { "tipodeconsulta": 1, "fecha1": fecha1, "fecha2": fecha2 });
            ajax_cat('/cat_padres', 'POST', { "tipodeconsulta": 1, "fecha1": fecha1, "fecha2": fecha2 });
            break;
        case 2:
            ajax('/transacciones', 'POST', { "tipodeconsulta": 2 });
            ajax_cat('/cat_padres', 'POST', { "tipodeconsulta": 2 });
            break;
        case 3:
            ajax('/transacciones', 'POST', { "tipodeconsulta": 3 });
            ajax_cat('/cat_padres', 'POST', { "tipodeconsulta": 3 });
            break;
        case 4:
            var mes = document.getElementById('meses').value;
            ajax('/transacciones', 'POST', { "tipodeconsulta": 4, "mes": mes });
            ajax_cat('/cat_padres', 'POST', { "tipodeconsulta": 4, "mes": mes });
            break;
        case 5:
            var anno = document.getElementById('años').value;
            ajax('/transacciones', 'POST', { "tipodeconsulta": 5, "anno": anno });
            ajax_cat('/cat_padres', 'POST', { "tipodeconsulta": 5, "anno": anno });
            break;
        default:
            break;
    }
}

function mostrarElementos() {
    $('#filtros').on('change', function () {
        $('#borrar_filtro').attr('hidden', false);
        var tipo_filtro = this.value;
        if (tipo_filtro == 1) {
            $('#rangos').attr('hidden', false);
            document.getElementById('fecha1').value = new Date().toISOString().substring(0, 10);
            document.getElementById('fecha2').value = new Date().toISOString().substring(0, 10);
        }
        // No hay 2 y 3 por que no hay elementos que mostrar
        if (tipo_filtro == 4) {
            $('#mes_calend').attr('hidden', false);
        }
        if (tipo_filtro == 5) {
            $('#año_calend').attr('hidden', false);
            var $selectAnnos = $('#años');
            var myDate = new Date();
            var year = myDate.getFullYear();
            for (var i = 1900; i < year + 1; i++) {
                $selectAnnos.append('<option value=' + i + '>' + i + '</option>');
            }

        }
    });
}

function ajax(url, type, consulta) {
    if (type == 'GET') {
        $.ajax(
            {
                type: type,
                url: url,
                dataType: "json",
                async: true,
                contentType: "application/json; charset=utf-8",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    console.log(data);
                },
            }
        )
    }
    else {
        $.ajax(
            {
                type: type,
                url: url,
                dataType: "json",
                async: true,
                contentType: "application/json; charset=utf-8",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify(consulta),
                success: function (data) {
                    console.log(data);
                    drawChart(data);
                },
            }
        )
    }
}

function ajax_cat(url, type, consulta) {
    $.ajax(
        {
            type: type,
            url: url,
            dataType: "json",
            async: true,
            contentType: "application/json; charset=utf-8",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(consulta),
            success: function (data) {
                console.log(data);
                temp = data;
            },
        }
    )
}

async function ajax_cat_hijos(url, type, consulta) {
    let response = "";
    await $.ajax(
        {
            type: type,
            url: url,
            dataType: "json",
            async: true,
            contentType: "application/json; charset=utf-8",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(consulta),
            success: function (data) {
                response = data;
            },
        }
    )
    return response;
}

async function ajax_cat_hijos_transacciones(url, type, consulta) {
    let response = "";
    await $.ajax(
        {
            type: type,
            url: url,
            dataType: "json",
            async: true,
            contentType: "application/json; charset=utf-8",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(consulta),
            success: function (data) {
                response = data;
            },
        }
    )
    return response;
}

// Load the Visualization API and the corechart package.
google.charts.load('current', {
    'packages': ['corechart', 'table']
});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart(value) {
    // Create the data table.
    let arrayTemp = value;
    for (var key in arrayTemp) {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
            [key, parseFloat(arrayTemp[key])],
        ]);

        // Set chart options
        let colorTemp = key == "ingresos" ? ['#00cc7f'] : ['#ef6c2b'];
        var options = {
            pieHole: 0.5,
            pieSliceTextStyle: {
                color: 'black',
            },
            'title': `Total de ${key}`,
            'width': 400,
            'height': 300,
            colors: colorTemp
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById(`donut_single_${key}`));

        drawButton(key);
        chart.draw(data, options);
    }
}

function drawButton(key) {
    var div = document.getElementById(`footer_${key}`);
    var button = document.createElement("button");
    button.id = key;
    button.value = key;
    button.classList.add("btn", "btn-primary");
    button.addEventListener('click', getValue, false);
    button.innerHTML = `Desglose de ${key}`;
    div.appendChild(button);
}

function getValue(event) {
    $('#cat_padres').removeAttr('hidden');
    let buttonValue = event.target.id;
    var combo = document.getElementById("filtros");
    seleccion = parseInt(combo.options[combo.selectedIndex].value);
    switch (seleccion) {
        case 1:
            var fecha1 = document.getElementById('fecha1').value;
            var fecha2 = document.getElementById('fecha2').value;
            ajax_cat('/cat_padres', 'POST', { "tipodeconsulta": 1, "fecha1": fecha1, "fecha2": fecha2 });
            break;
        case 2:
            ajax_cat('/cat_padres', 'POST', { "tipodeconsulta": 2 });
            break;
        case 3:
            ajax_cat('/cat_padres', 'POST', { "tipodeconsulta": 3 });
            break;
        case 4:
            var mes = document.getElementById('meses').value;
            ajax_cat('/cat_padres', 'POST', { "tipodeconsulta": 4, "mes": mes });
            break;
        case 5:
            var anno = document.getElementById('años').value;
            ajax_cat('/cat_padres', 'POST', { "tipodeconsulta": 5, "anno": anno });
            break;
        default:
            break;
    }
    drawChart_cat_padres(buttonValue, temp);
}

function drawChart_cat_padres(buttonValue, catPadres) {
    // Create the data table.
    var data = new google.visualization.DataTable();
    let cat_padre_ingresos = catPadres.cat_padre_ingresos;
    let cat_padre_gastos = catPadres.cat_padre_gastos;
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Slices');
    if (buttonValue == 'ingresos') {
        for (let index = 0; index < cat_padre_ingresos.length; index++) {
            let arrayTemp1 = cat_padre_ingresos[index];
            for (key in arrayTemp1) {
                data.addRows([[arrayTemp1['descripcion'], parseFloat(arrayTemp1['suma'])]]);
                break;
            }

        }
    }
    else {
        for (let index = 0; index < cat_padre_gastos.length; index++) {
            let arrayTemp2 = cat_padre_gastos[index];
            for (key in arrayTemp2) {
                data.addRows([[arrayTemp2['descripcion'], parseFloat(arrayTemp2['suma'])]]);
                break;
            }

        }
    }

    // Set chart options
    var options = {
        pieHole: 0.5,
        pieSliceTextStyle: {
            color: 'black',
        },
        'title': `Desgloce de ${buttonValue}`,
        'width': 400,
        'height': 300
    };

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('donut_single_cate_padre'));

    function selectHandler() {
        let response = "";
        let promise = "";
        var selectedItem = chart.getSelection()[0];
        if (selectedItem) {
            var categoria = data.getValue(selectedItem.row, 0);
            console.log(seleccion);
            switch (seleccion) {
                case 1:
                    var fecha1 = document.getElementById('fecha1').value;
                    var fecha2 = document.getElementById('fecha2').value;
                    response = ajax_cat_hijos('/cat_hijos', 'POST', { "tipodeconsulta": 1, "fecha1": fecha1, "fecha2": fecha2, "categoria": categoria });
                    promise = Promise.resolve(response);
                    console.log(promise);
                    promise.then((value) => {
                        if(value.cat_ingresos.length === 0 && buttonValue == 'ingresos'){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 1, "fecha1": fecha1, "fecha2": fecha2, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        if(value.cat_gastos.length === 0 && buttonValue == 'gastos'){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 1, "fecha1": fecha1, "fecha2": fecha2, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        else{
                            drawChart_cat_hijos(value, buttonValue)
                        }
                    });
                    break;
                case 2:
                    response = ajax_cat_hijos('/cat_hijos', 'POST', { "tipodeconsulta": 2, "categoria": categoria });
                    promise = Promise.resolve(response);
                    promise.then((value) => {
                        if(value.cat_ingresos.length === 0 && buttonValue == 'ingresos'){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 2, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        if(value.cat_gastos.length === 0 && buttonValue == 'gastos'){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 2, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        else{
                            drawChart_cat_hijos(value, buttonValue)
                        }
                    });
                    break;
                case 3:
                    response = ajax_cat_hijos('/cat_hijos', 'POST', { "tipodeconsulta": 3, "categoria": categoria });
                    promise = Promise.resolve(response);
                    promise.then((value) => {
                        if(value.cat_ingresos.length === 0 && buttonValue == 'ingresos'){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 3, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        if(value.cat_gastos.length === 0 && buttonValue == 'gastos'){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 3, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        else{
                            drawChart_cat_hijos(value, buttonValue)
                        }
                    });
                    break;
                case 4:
                    var mes = document.getElementById('meses').value;
                    response = ajax_cat_hijos('/cat_hijos', 'POST', { "tipodeconsulta": 4, "mes": mes, "categoria": categoria });
                    promise = Promise.resolve(response);
                    promise.then((value) => {
                        if(value.cat_ingresos.length === 0 && buttonValue == 'ingresos'){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 4, "mes": mes, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        if(value.cat_gastos.length === 0 && buttonValue == 'gastos'){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 4, "mes": mes, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        else{
                            drawChart_cat_hijos(value, buttonValue)
                        }
                    });
                    break;
                case 5:
                    var anno = document.getElementById('años').value;
                    response = ajax_cat_hijos('/cat_hijos', 'POST', { "tipodeconsulta": 5, "anno": anno, "categoria": categoria });
                    promise = Promise.resolve(response);
                    promise.then((value) => {
                        if(value.cat_ingresos.length === 0 && buttonValue == 'ingresos'){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 5, "anno": anno, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        if(value.cat_gastos.length === 0 && buttonValue == 'gastos'){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 5, "anno": anno, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        else{
                            drawChart_cat_hijos(value, buttonValue)
                        }
                    });
                    break;
                default:
                    break;
            }
        }
    }

    // Listen for the 'select' event, and call my function selectHandler() when
    // the user selects something on the chart.
    google.visualization.events.addListener(chart, 'select', selectHandler);

    chart.draw(data, options);
}

function drawChart_cat_hijos(value, buttonValue) {
    // Create the data table.
    var data = new google.visualization.DataTable();
    console.log(value);
    let cat_ingresos = value.cat_ingresos;
    let cat_gastos = value.cat_gastos;
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Slices');
    if (buttonValue == 'ingresos') {
        for (let index = 0; index < cat_ingresos.length; index++) {
            let arrayTemp1 = cat_ingresos[index];
            for (key in arrayTemp1) {
                data.addRows([[arrayTemp1['descripcion'], parseFloat(arrayTemp1['suma'])]]);
                break;
            }

        }
    }
    else {
        for (let index = 0; index < cat_gastos.length; index++) {
            let arrayTemp2 = cat_gastos[index];
            for (key in arrayTemp2) {
                data.addRows([[arrayTemp2['descripcion'], parseFloat(arrayTemp2['suma'])]]);
                break;
            }

        }
    }

    // Set chart options
    var options = {
        pieHole: 0.5,
        pieSliceTextStyle: {
            color: 'black',
        },
        'title': `Desgloce de ${buttonValue}`,
        'width': 400,
        'height': 300
    };

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('donut_single_cate_padre'));

    function selectHandler() {
        let response = "";
        let promise = "";
        var selectedItem = chart.getSelection()[0];
        if (selectedItem) {
            var categoria = data.getValue(selectedItem.row, 0);
            switch (seleccion) {
                case 1:
                    var fecha1 = document.getElementById('fecha1').value;
                    var fecha2 = document.getElementById('fecha2').value;
                    response = ajax_cat_hijos('/cat_hijos', 'POST', { "tipodeconsulta": 1, "fecha1": fecha1, "fecha2": fecha2, "categoria": categoria });
                    promise = Promise.resolve(response);
                    console.log(promise);
                    promise.then((value) => { 
                        if(value.cat_ingresos.length === 0 && value.cat_gastos.length === 0){
                            console.log('entró');
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 1, "fecha1": fecha1, "fecha2": fecha2, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        else{
                            drawChart_cat_hijos(value, buttonValue) 
                        }
                    });
                    break;
                case 2:
                    response = ajax_cat_hijos('/cat_hijos', 'POST', { "tipodeconsulta": 2, "categoria": categoria });
                    promise = Promise.resolve(response);
                    promise.then((value) => {
                        if(value.cat_ingresos.length === 0 && value.cat_gastos.length === 0){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 2, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        else{
                            drawChart_cat_hijos(value, buttonValue) 
                        }
                    });
                    break;
                case 3:
                    response = ajax_cat_hijos('/cat_hijos', 'POST', { "tipodeconsulta": 3, "categoria": categoria });
                    promise = Promise.resolve(response);
                    promise.then((value) => {
                        if(value.cat_ingresos.length === 0 && value.cat_gastos.length === 0){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 3, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        else{
                            drawChart_cat_hijos(value, buttonValue)
                        }
                    });
                    break;
                case 4:
                    var mes = document.getElementById('meses').value;
                    response = ajax_cat_hijos('/cat_hijos', 'POST', { "tipodeconsulta": 4, "mes": mes, "categoria": categoria });
                    promise = Promise.resolve(response);
                    promise.then((value) => {
                        if(value.cat_ingresos.length === 0 && value.cat_gastos.length === 0){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 4, "mes": mes, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        else{
                            drawChart_cat_hijos(value, buttonValue) 
                        }
                    });
                    break;
                case 5:
                    var anno = document.getElementById('años').value;
                    response = ajax_cat_hijos('/cat_hijos', 'POST', { "tipodeconsulta": 5, "anno": anno, "categoria": categoria });
                    promise = Promise.resolve(response);
                    promise.then((value) => {
                        if(value.cat_ingresos.length === 0 && value.cat_gastos.length === 0){
                            response = ajax_cat_hijos_transacciones('/cat_hijos_transacc', 'POST', { "tipodeconsulta": 5, "anno": anno, "categoria": categoria });
                            promise = Promise.resolve(response);
                            promise.then((value) => { tableChart(value, buttonValue)});
                        }
                        else{
                            drawChart_cat_hijos(value, buttonValue)
                        }
                    });
                    break;
                default:
                    break;
            }

        }
    }

    // Listen for the 'select' event, and call my function selectHandler() when
    // the user selects something on the chart.
    google.visualization.events.addListener(chart, 'select', selectHandler);

    chart.draw(data, options);
}

function tableChart(value, buttonValue) {
    // Create the data table.
    var data = new google.visualization.DataTable();
    console.log(value);
    let cat_ingresos_transacc = value.cat_ingresos_transacc;
    let cat_gastos_transacc = value.cat_gastos_transacc;
    data.addColumn('string', 'Descripcion');
    data.addColumn('string', 'Detalle');
    data.addColumn('number', 'monto');
    data.addColumn('string', 'Nombre');
    data.addColumn('string', 'Tipo');
    if (buttonValue == 'ingresos') {
        for (let index = 0; index < cat_ingresos_transacc.length; index++) {
            let arrayTemp1 = cat_ingresos_transacc[index];
            for (key in arrayTemp1) {
                data.addRows([[arrayTemp1['descripcion'], arrayTemp1['detalle'], parseFloat(arrayTemp1['monto']), arrayTemp1['nombre'], arrayTemp1['tipo']]]);
                break;
            }

        }
    }
    else {
        for (let index = 0; index < cat_gastos_transacc.length; index++) {
            let arrayTemp2 = cat_gastos_transacc[index];
            for (key in arrayTemp2) {
                data.addRows([[arrayTemp2['descripcion'], arrayTemp2['detalle'], parseFloat(arrayTemp2['monto']), arrayTemp2['nombre'], arrayTemp2['tipo']]]);
                break;
            }

        }
    }

    var table = new google.visualization.Table(document.getElementById('donut_single_cate_padre'));

    table.draw(data, { showRowNumber: true });


}