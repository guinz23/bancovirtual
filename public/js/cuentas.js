$(document).ready(function () {
    $('#tipo').on('change', function() {
        if (this.value == 'Traslado') {
            ajax_dos('/todascuentas', 'GET');
        }
        else {
            $('#destino').attr('hidden', true);
            $('#cuentas').attr('hidden', true);
        }
    });
});

function ajax_dos(url, type) {
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
                $('#destino').removeAttr('hidden');
                $('#cuentas').removeAttr('hidden');
                var $selectCuentas = $('#cuentas');
                data.destinos.forEach(element =>  $selectCuentas.append('<option value=' + element.id + '>' + element.nombre + '</option>'));
            },
        }
    )
}