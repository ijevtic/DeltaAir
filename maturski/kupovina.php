<?php
    require 'header.php';    
?>
<style>
    #map {
        height: 100%;
        width: 65%;
        border: 1.5px solid #bbb;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js"></script>
<script src="js/pdf.js"></script>
<script src="js/formatDatumVreme.js"></script> <!-- funkcije za formatiranje datuma i vremena -->
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;400&display=swap" rel="stylesheet">
<div class="d-flex w-60 justify-content-between" style=" margin-left : 5%; margin-top : 1.6%; margin-bottom:1%;">
    <h1 class="display-3" style="margin-left : 5%; margin-top : 1.6%; margin-bottom:1%; font-family:'Jost', serif;">
        Račun</h1>
    <p
        style="svg:hover {fill: red;} display: flex; justify-content: center; align-items: center; margin-right:3%; margin-top:2%;">
        <button id="pdf" class="btn btn-primary" style="border-radius: 20px;"><i class="far fa-file-alt fa-2x"></i>
        </button>
    </p>
</div>


<div class="row" style="margin-left:4%; margin-right:2%; border-left: 2px solid black; height : 400px;">
    <div style="width:35%">
        <div id="cont" class="col-lg">
            <div id="fir" class="row justify-content-between" style=" width:100%;">
                <div class=" text-left" style="margin-left: 0.5%; margin-top: auto; margin-bottom:auto;">
                    <i style="font-family: 'Roboto Slab', serif; font-size:20px; ">Prva klasa</i>
                </div>
                <div class="text-right">
                    <i id="tekstFir" style="font-family: 'Roboto Slab', serif; font-size:24px;"></i>
                </div>
            </div>
            <div id="bus" class="row justify-content-between" style=" width:100%;">
                <div class=" text-left" style="margin-left: 0.5%; margin-top: auto; margin-bottom:auto;">
                    <i style="font-family: 'Roboto Slab', serif; font-size:20px; ">Biznis klasa</i>
                </div>
                <div class="text-right">
                    <i id="tekstBus" style="font-family: 'Roboto Slab', serif; font-size:24px;"></i>
                </div>
            </div>
            <div id="eco" class="row justify-content-between" style=" width:100%;">
                <div class=" text-left" style="margin-left: 0.5%; margin-top: auto; margin-bottom:auto;">
                    <i style="font-family: 'Roboto Slab', serif; font-size:20px; ">Ekonomska klasa</i>
                </div>
                <div class="text-right">
                    <i id="tekstEco" style="font-family: 'Roboto Slab', serif; font-size:24px;"></i>
                </div>
            </div>
            <div width="100%" style="overflow:hidden;">
                <div style="margin-top:2px; width:55%; border-bottom: 2.5px solid #bbb; float:right;"></div>
            </div>
            <div class="col-md text-right" style=" width:100%; text-align: right;">
                <i id="cena" style="font-family: 'Roboto Slab', serif; font-size:31px; font-weight:700;"></i>
            </div>

            <div class="row justify-content-between" style="width:100%;">
                <div class=" text-left" style="margin-left: 0.5%; margin-top: auto; margin-bottom:auto;">
                    <p style="font-family: 'Roboto Slab', serif; font-size:20px; ">Datum putovanja:</p>
                </div>
                <div class="text-right">
                    <p id="datum" style="font-family: 'Roboto Slab', serif; font-size:24px;"></p>
                </div>
            </div>
            <div class="row justify-content-between" style=" width:100%;">
                <div class=" text-left" style="margin-left: 0.5%; margin-top: auto; margin-bottom:auto;">
                    <p style="font-family: 'Roboto Slab', serif; font-size:20px; ">Broj kupljenih karata:</p>
                </div>
                <div class="text-right">
                    <p id="brKarata" style="font-family: 'Roboto Slab', serif; font-size:24px;"></p>
                </div>
            </div>
        </div>


        <button id="btnKupi" class="btn btn-primary"
            style="float:right; font-family: 'Roboto Slab', serif; font-size:20px; margin-right:1.5%;"><i
                class="fas fa-check"></i> Završi kupovinu</button>
    </div>



    <div id="map" class="map"></div>
</div>
<script>
    var map;
    var nacrtan = false;
    var kol = 0;
    var doc;

    function ocisti(val, id) {
        if (val > 0) return false;
        document.getElementById(id).remove();
        return true;
    }

    function danasnji_datum() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        return today;
        //return ispisiDatum(today) + ".";
    }

    function popuni() {
        document.getElementById("cena").innerHTML = podaci.cena + "RSD";
        var plus = "";
        if (podaci.broj == 2) {
            if (!ocisti(podaci.fir.kol, "fir")) {
                var tekst1 = podaci.fir.kol + " &times; " + podaci.fir.cena1;
                document.getElementById("tekstFir").innerHTML = tekst1;
                plus = "+ ";
            }
            if (!ocisti(podaci.bus.kol, "bus")) {
                var tekst2 = plus + podaci.bus.kol + " &times; " + podaci.bus.cena1;
                document.getElementById("tekstBus").innerHTML = tekst2;
                plus = "+ ";
            }
            if (!ocisti(podaci.eco.kol, "eco")) {
                var tekst3 = plus + podaci.eco.kol + " &times; " + podaci.eco.cena1;
                document.getElementById("tekstEco").innerHTML = tekst3;
            }
        } else {
            plus = "";
            if (!ocisti(podaci.fir.kol, "fir")) {
                var tekst1 = podaci.fir.kol + " &times; (" + podaci.fir.cena1 + "+" + podaci.fir.cena2 + ")";
                document.getElementById("tekstFir").innerHTML = tekst1;
                plus = "+ ";
            }
            if (!ocisti(podaci.bus.kol, "bus")) {
                var tekst2 = plus + podaci.bus.kol + " &times; (" + podaci.bus.cena1 + "+" + podaci.bus.cena2 + ")";
                document.getElementById("tekstBus").innerHTML = tekst2;
                plus = "+ ";
            }
            if (!ocisti(podaci.eco.kol, "eco")) {
                var tekst3 = plus + podaci.eco.kol + " &times; (" + podaci.eco.cena1 + "+" + podaci.eco.cena2 + ")";
                document.getElementById("tekstEco").innerHTML = tekst3;
            }
        }
        kol = parseInt(podaci.fir.kol) + parseInt(podaci.bus.kol) + parseInt(podaci.eco.kol)
        document.getElementById("brKarata").innerHTML = kol;
        document.getElementById("datum").innerHTML = ispisiDatum(podaci.let1.datum) + ".";
        doc = praviPdf();
    }


    var btnPdf = document.getElementById("pdf");
    btnPdf.addEventListener("click", function () {

        var string = doc.output('datauristring');
        var iframe = "<iframe width='100%' height='100%' src='" + string + "'></iframe>"
        var x = window.open();
        x.document.open();
        x.document.write(iframe);
        x.document.close();
    });

    var btnKupi = document.getElementById("btnKupi");
    btnKupi.addEventListener("click", function () {

        var id_leta1 = podaci.let1.id_leta;
        var id_leta2 = -1;
        if (podaci.broj == 3) id_leta2 = podaci.let2.id_leta;
        $.post("baza/kupiKartu.php", {
                broj: podaci.broj,
                cnt_fir: podaci.fir.kol,
                cnt_bus: podaci.bus.kol,
                cnt_eco: podaci.eco.kol,
                id_leta1: id_leta1,
                id_leta2: id_leta2,
                id_putnika: podaci.id_kupca,
                poslat: true
            },
            function (data, status) {
                console.log(data);
                window.location.replace("login.php");
            });
    });

    function crtaj(l1, l2, l3, map) {
        if (nacrtan) return;
        nacrtan = true;
        var p1 = l1;
        var p2 = l2;
        var p3 = l3;

        var Map = google.maps.Map,
            LatLng = google.maps.LatLng,
            LatLngBounds = google.maps.LatLngBounds,
            Marker = google.maps.Marker,
            Point = google.maps.Point;

        var pos1 = new LatLng(l1.lat, l1.lng);
        var pos2 = new LatLng(l2.lat, l2.lng);
        if (podaci.broj == 3) var pos3 = new LatLng(l3.lat, l3.lng);
        var bounds = new LatLngBounds();
        bounds.extend(pos1);
        bounds.extend(pos2);
        if (podaci.broj == 3) bounds.extend(pos3);

        map.fitBounds(bounds);

        var markerP1 = new Marker({
            position: pos1,
            label: "1",
            map: map

        });
        var markerP2 = new Marker({
            position: pos2,
            label: "2",
            map: map
        });
        if (podaci.broj == 3) {
            var markerP3 = new Marker({
                position: pos3,
                label: "3",
                map: map
            });
        }

        if (podaci.broj == 2) {
            var flightPlanCoordinates = [{
                    lat: l1.lat,
                    lng: l1.lng
                },
                {
                    lat: l2.lat,
                    lng: l2.lng
                }
            ];
        } else {
            var flightPlanCoordinates = [{
                    lat: l1.lat,
                    lng: l1.lng
                },
                {
                    lat: l2.lat,
                    lng: l2.lng
                },
                {
                    lat: l3.lat,
                    lng: l3.lng
                }
            ];
        }

        var flightPath = new google.maps.Polyline({
            path: flightPlanCoordinates,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 0.6,
            strokeWeight: 5
        });
        flightPath.setMap(map);
    }

    var podaci;
    var ajaxDone = false;
    var mapDone = false;
    var myLatLng1;
    var myLatLng2;
    var myLatLng3;


    $.get("baza/procitajKartu.php?poslat=true", function (data) {

        console.log(data);
        podaci = JSON.parse(data);
        if (podaci == "ne") document.location.href = "login.php?access_denied";
        ajaxDone = true;
        podaci.grad1.lokacija_x = parseFloat(podaci.grad1.lokacija_x);
        podaci.grad1.lokacija_y = parseFloat(podaci.grad1.lokacija_y);
        podaci.grad2.lokacija_x = parseFloat(podaci.grad2.lokacija_x);
        podaci.grad2.lokacija_y = parseFloat(podaci.grad2.lokacija_y);
        if (podaci.broj == 3) {
            podaci.grad3.lokacija_x = parseFloat(podaci.grad3.lokacija_x);
            podaci.grad3.lokacija_y = parseFloat(podaci.grad3.lokacija_y);
        }
        myLatLng1 = {
            lat: parseFloat(podaci.grad1.lokacija_x),
            lng: parseFloat(podaci.grad1.lokacija_y)
        };
        myLatLng2 = {
            lat: parseFloat(podaci.grad2.lokacija_x),
            lng: parseFloat(podaci.grad2.lokacija_y)
        };
        if (podaci.broj == 2) myLatLng3 = 0;
        else {
            myLatLng3 = {
                lat: parseFloat(podaci.grad3.lokacija_x),
                lng: parseFloat(podaci.grad3.lokacija_y)
            };
        }
        if (mapDone)
            crtaj(myLatLng1, myLatLng2, myLatLng3, map);
        popuni();
    });

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: 0,
                lng: 0
            },
            zoom: 10
        });
        mapDone = true;
        if (ajaxDone)
            crtaj(myLatLng1, myLatLng2, myLatLng3, map);
    }
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6gAQk35vgf55fk4BMcfqpBFPwA5MLrNY&callback=initMap">
</script>

<?php
 require 'footer.php';
?>