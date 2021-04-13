$(document).ready(function () {
    $('#namecoin').on('change', function() {
        if (this.value == 'otra') {
            var $selectCoins = $('#formOtra');
            $selectCoins.append('<br>');
            $selectCoins.append('<label>Nombre de la moneda</label>');
            $selectCoins.append('<input type="text" name="name" class="form-control" placeholder="Escribe el nombre de la moneda" required></input>');
            
            $selectCoins.append('<br>');
            $selectCoins.append('<label>Símbolo de la moneda</label>');
            $selectCoins.append('<input type="text" name="simbolo" class="form-control" placeholder="Escribe el símbolo de la moneda" required></input>');
        
            $selectCoins.append('<br>');
            $selectCoins.append('<label>Descripción de la moneda</label>');
            $selectCoins.append('<input type="text" name="desc" class="form-control" placeholder="Escribe una breve descripción de la moneda" required></input>');
        }
        else {
            $('#formOtra').attr('hidden', true);
        }
    });
});