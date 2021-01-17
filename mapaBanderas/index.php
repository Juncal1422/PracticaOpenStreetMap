<!DOCTYPE html>
<?php
require 'config.php';
?>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <title>Praias Bandera Azul</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet"/>
    <style type="text/css">
      #mapa {
        position:absolute;
        height:500px;
        width:500px;
        right: 20px;
        top: 80px;
    }
    #sugerencias{
        position:absolute;
        top: 60px;
        left: 80px;
        width: 200px;
        height: 100px;
    }
    .concello{
        background-color: #99C8CC;
        border:1px solid #80B3C7;

    }
    .concello:hover{
        background-color: #55E0AA;
        cursor:pointer;
    }

</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
</head>
<body>
    <div id="formulario">
        <div class="navbar navbar-expand-sm bg-light">
            <label for="inputConcello">Concello:</label>
            <input type="text" class="p-2 m-2 form-control" name="inputConcello" id="inputConcello"/>
        </div>
    </div>
    <table id="sugerencias"> </table>
    <div id="mapa"></div>
    <div id="meteo"></div>
    <script src="../js/jquery.js"></script>
    <script>
        $("#sugerencias").hide();

        //MAPA//
        var mapa = L.map('mapa').setView([42.8805200, -8.5456900], 13);
        
        var arrPlayas=[];
        var arrConcellos=[];

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
          maxZoom: 13
      }).addTo(mapa);
        L.control.scale().addTo(mapa);

        $("#mapa").show();




        //FIN MAPA//

        $("#inputConcello").keyup(function(event) {
            var concello= $.trim($(this).val());
            console.log(concello);
            $.get('sugerir.php', {
                concello:concello
            },function(datos){
                $("#sugerencias").html(datos);
                $("#sugerencias").show();
            });
        });

        function centrarMapa(codigoconcello){
            $.get('consultarConcello.php',{
                codigoconcello:codigoconcello
            },function(datos){
                if(arrConcellos.length!=0){
                    mapa.removeLayer(arrConcellos[0]);
                }
                arrConcellos=[];
                var datosDev = JSON.parse(datos);
                console.log(datosDev.datos.concello);
                mapa.panTo(new L.LatLng(datosDev.datos.concello.lat, datosDev.datos.concello.lon));
                var greenIcon = new L.Icon({
                  iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png'
              });
                var casaconcello = L.marker([datosDev.datos.concello.lat, datosDev.datos.concello.lon], {icon: greenIcon});
                mapa.addLayer(casaconcello);
                casaconcello.bindPopup(datosDev.datos.concello.enderezo+"<br>Telefono: "+datosDev.datos.concello.telefono+"<br>Fax: "+datosDev.datos.concello.fax+"<br>Páxina Web: "+datosDev.datos.concello.web);

                arrConcellos[0]=casaconcello;
            });
        }
        function fijarPlayas(codigoconcello){
            $.get('consultarConcello.php',{
                codigoconcello:codigoconcello
            },function(datos){
                var datosDev = JSON.parse(datos);
                for(var i = 0; i<arrPlayas.length;i++){
                    mapa.removeLayer(arrPlayas[i]);
                }
                arrPlayas=[];
                console.log(datosDev.datos.playas);
                for(var i = 0; i<datosDev.datos.playas.length;i++){
                    var playa = L.marker(datosDev.datos.playas[i].coordenadas.split(','));
                    mapa.addLayer(playa);
                    playa.bindPopup('Nombre de la playa: '+datosDev.datos.playas[i].praia+'<br>Longitud: '+datosDev.datos.playas[i].lonxitude+'<br>Tipo de praia: '+datosDev.datos.playas[i].tipo+'<br>Tipo de Area: '+datosDev.datos.playas[i].tipoarea);
                    mapa.on('popupopen', function(event){
                      var url = "https://servizos.meteogalicia.es/apiv3/getNumericForecastInfo?coords=lon,lat&format=text/html&startTime=2020-02-06T10:00:00&endTime=2020-02-06T15:00:00&API_KEY=lfhKfT4NX175i5SeyrFMp56KDGI01Ms0zsGKA73qlEV3z51jRj05thBPfixiDrac";
                      $.getJSON(url, function(response){
                        $("#meteo").html(response);
                    });
                  });
                    arrPlayas[i]=playa;
                }
            });
        }
    </script>
</body>
</html>