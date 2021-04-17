<?php
require "header.php";

?>
<style>
  .square {
    width: 100%;
    height: 100%;
    border-top: 0.5px solid #bbb;
    /*border: 0.5px solid #bbb;*/
    pointer-events: none;
  }

  .carousel-content {
    position: absolute;
    top: 15%;
    left: 10%;
  }

  .predji:hover {
    color: #2398D8;
    cursor: pointer;
  }

  .hover10 img {
    -webkit-filter: grayscale(0) blur(0);
    filter: grayscale(0) blur(0);
    -webkit-transition: .15s ease-in-out;
    transition: .15s ease-in-out;
  }

  .hover10:hover img {
    -webkit-filter: grayscale(100%) blur(3px);
    filter: grayscale(100%) blur(3px);
    cursor: pointer;
  }

  .centered {
    color: white;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    /*vidi sta radi*/
    visibility: hidden;
  }
</style>
<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@1,300;1,500&display=swap" rel="stylesheet">
<script src="js/preloadSlika.js"></script>
<script src="js/mapaStyle.js"></script>

<div id="carouselExampleIndicators" class="carousel slide lazy" data-ride="carousel">
  <div class="carousel-inner" style="height:100%;">
    <div class="carousel-item active" style="height:100%;">
      <div id="map" class="square d-block w-100 " style="height:100%;"></div>
      <div class="carousel-content">
        <h1 class="display-1"
          style="font-size:110px; font-style: italic; font-family:'Jost', 'serif'; color:white; font-weight:500;">Delta
          Air</h1>
      </div>
    </div>
  </div>
</div>

<div class="container marketing mt-5">
  <div class="row featurette" style=" text-align:center;">
    <div class="col-lg-5">
      <div id="img_dest" class="hover10 column">
        <img class="d-block w-100" src="slike/globe.jpg" alt="Second slide">
        <div id="img_dest_icon" class="centered"><i class="fas fa-search-location fa-3x"></i></div>
      </div>
    </div>
    <div class="col-lg-7" style="margin:auto;">
      <i class="fas fa-globe-europe fa-5x"></i>
      <h1 class="display-4" style="font-family:'Jost'; font-style: italic; font-weight:300;">Destinacije</h1>
      <p style="font-size:17px;">Pronađi sve postojeće linije iz grada koji izabereš.</p>
    </div>
  </div>
  <hr class="featurette-divider">
  <div class="row featurette" style=" text-align:center;">
    <div class="col-lg-5" style="margin:auto;">
      <i class="fas fa-location-arrow fa-5x"></i>
      <h1 class="display-4" style="font-family:'Jost'; font-style: italic; font-weight:300;">Pretraga letova</h1>
      <p style="font-size:17px;">Odaberi početnu i krajnju lokaciju i pronađi let koji ti najviše odgovara.</p>
    </div>
    <div class="col-lg-7">
      <div id="img_let" class="hover10 column">
        <img class="d-block w-100" src="slike/plane2.jpg" alt="Second slide">
        <div id="img_let_icon" class="centered"><i class="fas fa-search fa-3x"></i></div>
      </div>
    </div>
  </div>
  <hr class="featurette-divider">
  <div class="row featurette" style=" text-align:center;">
    <div class="col-sm-12">
      <i id="icon_profil" class="fas fa-user fa-6x predji"></i>
      <h1 class="display-4" style="font-family:'Jost'; font-style:italic; font-weight:300;">Moj profil</h1>
      <p style="font-size:17px;">Klikom na ikonicu poseti svoj profil. Pogledaj kupljene karte i predstojeće letove.
        Prati istoriju dosadašnjih putovanja.</p>
    </div>
  </div>
</div>

<script>
  preloadImages(["slike/plane.jpg"]);

  console.log(document.getElementById("carouselExampleIndicators").offsetWidth);
  document.getElementById("carouselExampleIndicators").style.height = document.getElementById(
    "carouselExampleIndicators").offsetWidth / 3 + "px";
  //document.getElementById("map").style.width = document.getElementById("carouselExampleIndicators").offsetWidth/3 + "px";


  var iconProfil = document.getElementById("icon_profil");
  iconProfil.addEventListener("click", function () {
    if (document.getElementById("prijava_forma") == null)
      window.location.replace("profil.php");
  });

  var imgDest = document.getElementById("img_dest");
  imgDest.addEventListener("mouseover", function () {
    document.getElementById("img_dest_icon").style.visibility = "visible";
    console.log("Over");
  });

  imgDest.addEventListener("mouseleave", function () {
    document.getElementById("img_dest_icon").style.visibility = "hidden";
    console.log("Not over");
  });

  imgDest.addEventListener("click", function () {
    window.location.replace("destinacije.php");
  });

  var imgLet = document.getElementById("img_let");
  imgLet.addEventListener("mouseover", function () {
    document.getElementById("img_let_icon").style.visibility = "visible";
    console.log("Over");
  });

  imgLet.addEventListener("mouseleave", function () {
    document.getElementById("img_let_icon").style.visibility = "hidden";
    console.log("Not over");
  });

  imgLet.addEventListener("click", function () {
    window.location.replace("letovi.php");
  });

  var map;
  var napravio = false;
  var procitao = false;
  var nacrtao = false;
  var koordinate;
  var pocetni;
  var pos = [];
  var markeri = [];

  function crtaj() {

    if (nacrtao) return;
    nacrtao = true;
    var Map = google.maps.Map,
      LatLng = google.maps.LatLng,
      LatLngBounds = google.maps.LatLngBounds,
      Marker = google.maps.Marker,
      Point = google.maps.Point;
    var bounds = new LatLngBounds();
    var pocPos = new LatLng(pocetni.x, pocetni.y);
    for (var i = 0; i < koordinate.length; i++) {
      pos.push(new LatLng(koordinate[i].x, koordinate[i].y));
      bounds.extend(pos[i]);
    }
    map.fitBounds(bounds);
    var markerp = new Marker({
      position: pocPos,
      icon: {
        path: google.maps.SymbolPath.CIRCLE,
        strokeColor: "#0062D3",
        fillColor: "#0062D3",
        fillOpacity: 1,
        scale: 4
      },
      label: "",
      map: map
    });

    for (var i = 0; i < koordinate.length; i++) {
      var marker = new Marker({
        position: pos[i],
        label: "",
        icon: {
          path: google.maps.SymbolPath.CIRCLE,
          strokeColor: "#FF7B0E",
          fillColor: "#FF7B0E",
          fillOpacity: 1,
          scale: 2
        },
        map: map
      });
    }
    for (var i = 0; i < koordinate.length; i++) {
      var flightPlanCoordinates = [{
          lat: parseFloat(pocetni.x),
          lng: parseFloat(pocetni.y)
        },
        {
          lat: parseFloat(koordinate[i].x),
          lng: parseFloat(koordinate[i].y)
        }
      ];
      var flightPath = new google.maps.Polyline({
        path: flightPlanCoordinates,
        geodesic: true,
        strokeColor: 'white',
        strokeOpacity: 0.8,
        strokeWeight: 2
      });
      flightPath.setMap(map);

    }

  }
  window.addEventListener('load', function () {
    map.setZoom(map.getZoom() + 0.3);
  });
  $.post("baza/homeKoordinate.php", {
      poc: "Beograd",
      poslat: true
    },
    function (data, status) {
      console.log(data);
      var p = JSON.parse(data);
      koordinate = p.nizKoor;
      pocetni = p.poc_grad;
      procitao = true;
      if (napravio) crtaj();
    });


  function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
      center: {
        lat: -34.397,
        lng: 150.644
      },
      zoom: 8,
      styles: stilMape()
    });
    map.setOptions({
      draggable: false,
      disableDefaultUI: true,
      zoomControl: false,
      scrollwheel: false,
      disableDoubleClickZoom: true
    });
    napravio = true;
    if (procitao) crtaj();
  }
</script>

<div class="loader-wrapper">
  <span class="loader"><span class="loader-inner"></span></span>
</div>

<script>
  $(window).on("load", function () {
    $(".loader-wrapper").fadeOut("slow");
  });
</script>

<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6gAQk35vgf55fk4BMcfqpBFPwA5MLrNY&callback=initMap">
</script>

<?php
require "footer.php";
?>