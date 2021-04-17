<?php
require "header.php";

?>
<style>
  .map {
    height: 67%;
    width: 80%;
    margin: auto;
    border: 2.5px solid #bbb;
  }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6gAQk35vgf55fk4BMcfqpBFPwA5MLrNY"></script>
<link rel="stylesheet" type="text/css" href="dizajn/popup.css" />
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
<div id="listaGradova" class="list-group" style="height:100%;">
  <div>

    <script>
      function racunajRastojanje(lat1, lon1, lat2, lon2) {
        if ((lat1 == lat2) && (lon1 == lon2)) {
          return 0;
        } else {
          var radlat1 = Math.PI * lat1 / 180;
          var radlat2 = Math.PI * lat2 / 180;
          var theta = lon1 - lon2;
          var radtheta = Math.PI * theta / 180;
          var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
          if (dist > 1) {
            dist = 1;
          }
          dist = Math.acos(dist);
          dist = dist * 180 / Math.PI;
          dist = dist * 60 * 1.1515;
          dist = dist * 1.609344
          return dist;
        }
      }

      var listaGradova = document.getElementById("listaGradova");
      document.body.appendChild(listaGradova);

      function myFunction(id, imeGrada, koorX, koorY) {

        /*var url = window.location.pathname;
        url = url.concat('?id=', id, '&imeGrada=', imeGrada, "&koorX=", koorX, "&koorY=", koorY);
        console.log(url);
        window.location.href = url;*/
        var pocetni = new Object();
        pocetni = {
          id: id,
          ime: imeGrada,
          lokacija_x: koorX,
          lokacija_y: koorY
        };
        localStorage.setItem("pocetak", JSON.stringify(pocetni));
        location.reload();
      }
      var endCity;
      var url = window.location.href;

      function ajaxLoaded() {
        pocetni.lokacija_x = parseFloat(pocetni.lokacija_x);
        pocetni.lokacija_y = parseFloat(pocetni.lokacija_y);

        $.get("baza/noveLinije.php?pocetak=" + pocetni.id, function (data) {
          listaGradova.textContent = "";
          console.log(JSON.parse(localStorage.getItem("pocetak")));
          console.log(pocetni);
          console.log(data);
          endCity = JSON.parse(data);
          for (var i = 0; i < endCity.length; i++) {
            endCity[i].lokacija_x = parseFloat(endCity[i].lokacija_x);
            endCity[i].lokacija_y = parseFloat(endCity[i].lokacija_y);
            var card = document.createElement("card");
            card.classList.add("card");
            card.classList.add("card-image");
            card.setAttribute("style", "min-height:60%;");
            var imeSlike = "url('slike/" + endCity[i].ime.toLowerCase() + ".jpg')";
            var bgr = "linear-gradient(rgba(25, 25, 35, 0.6), rgba(25, 25, 35, 0.6)) , " + imeSlike;
            var opis = endCity[i].opis;
            card.style.background = bgr;
            card.style.backgroundRepeat = "no-repeat";
            card.style.backgroundSize = "cover";

            var sadrzaj = document.createElement("div");
            sadrzaj.classList.add("text-white", "text-center", "rgba-stylish-strong", "py-5", "px-4");
            var sadrzaj2 = document.createElement("div");
            sadrzaj2.classList.add("py-5");

            var h2 = document.createElement("h3");
            h2.classList.add("card-title", "h3", "my-4", "py-2");
            h2.setAttribute("style", "font-family:'Jost', serif; font-size:2.5rem;");
            h2.innerHTML = "<i class='fas fa-camera-retro'></i> " + endCity[i].ime;
            sadrzaj2.appendChild(h2);

            var par = document.createElement("p");
            par.classList.add("mb-4", "pb-2", "px-md-5", "mx-md-5");
            par.setAttribute("style", "font-family: 'Noto Sans JP', sans-serif;");
            par.innerHTML = opis;
            sadrzaj2.appendChild(par);

            var btnMapa = document.createElement("button");
            btnMapa.classList.add("btn", "btn-info");
            btnMapa.innerHTML = "<i class='fas fa-map'></i> Mapa";
            btnMapa.id = endCity[i].id;
            sadrzaj2.appendChild(btnMapa);

            var modal = document.createElement("div");
            modal.setAttribute("style", "text-align: center;");
            modal.classList.add("modal");
            modal.id = "modal" + endCity[i].id;

            var sadrzajModala = document.createElement("div");
            sadrzajModala.classList.add("modal-content");
            var span = document.createElement("span");
            span.setAttribute("class", "badge badge-danger mb-2");
            span.setAttribute("style", "margin:auto; display: inline-block; font-size: 18px; cursor: pointer;");
            span.style.color = "white";

            span.innerHTML = "&times;";
            span.id = "odal" + endCity[i].id;
            modal.appendChild(span);

            var mapa = document.createElement("div");
            mapa.classList.add("map");
            mapa.id = "map" + endCity[i].id;
            modal.appendChild(mapa);

            sadrzaj2.appendChild(modal);
            sadrzaj.appendChild(sadrzaj2);
            card.appendChild(sadrzaj);
            listaGradova.appendChild(card);
          }
          for (var i = 0; i < endCity.length; i++) {

            var p1 = {
              lat: pocetni.lokacija_x,
              lng: pocetni.lokacija_y
            };
            var p2 = {
              lat: endCity[i].lokacija_x,
              lng: endCity[i].lokacija_y
            };

            var Map = google.maps.Map,
              LatLng = google.maps.LatLng,
              LatLngBounds = google.maps.LatLngBounds,
              Marker = google.maps.Marker,
              Point = google.maps.Point;

            var pos1 = new LatLng(pocetni.lokacija_x, pocetni.lokacija_y);
            var pos2 = new LatLng(endCity[i].lokacija_x, endCity[i].lokacija_y);
            var bounds = new LatLngBounds();
            bounds.extend(pos1);
            bounds.extend(pos2);

            var map = new Map(document.getElementById("map" + endCity[i].id), {
              center: bounds.getCenter(),
              zoom: 12
            });
            var rastojanje = racunajRastojanje(pocetni.lokacija_x, pocetni.lokacija_y, endCity[i].lokacija_x, endCity[i]
              .lokacija_y);
            var priblizi;
            if (rastojanje < 600) priblizi = 7;
            else if (rastojanje < 1300) priblizi = 6;
            else if (rastojanje < 3600) priblizi = 4.7;
            else if (rastojanje < 5000) priblizi = 4;
            else priblizi = 3.5;
            map.setOptions({
              minZoom: priblizi
            });
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
            var flightPlanCoordinates = [{
                lat: pocetni.lokacija_x,
                lng: pocetni.lokacija_y
              },
              {
                lat: endCity[i].lokacija_x,
                lng: endCity[i].lokacija_y
              }
            ];
            var flightPath = new google.maps.Polyline({
              path: flightPlanCoordinates,
              geodesic: true,
              strokeColor: '#D30000',
              strokeOpacity: 1,
              strokeWeight: 4
            });
            flightPath.setMap(map);

          }
        });
      }

      $('#listaGradova').on('click', 'button', function (e) {
        var modal = document.getElementById("modal" + e.target.id);
        modal.style.display = "block";
        console.log("otvorio sam");
      });
      $('#listaGradova').on('click', 'span', function (e) {
        var modal = document.getElementById("m" + e.target.id);
        modal.style.display = "none";
        console.log("zatvoren");
      });
    </script>
    <!-- Ovo je loading -->

    <div class="loader-wrapper">
      <span class="loader"><span class="loader-inner"></span></span>
    </div>

    <script>
      $(window).on("load", function () {
        $(".loader-wrapper").fadeOut("slow");
      });
    </script>

    <!--<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6gAQk35vgf55fk4BMcfqpBFPwA5MLrNY&callback=initMap">
    </script>-->

    <?php
require "footer.php";
?>