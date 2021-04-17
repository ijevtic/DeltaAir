<?php

include 'header.php';
include 'baza/nizGradova.php';

?>
<script src="js/numeric.js"></script> <!-- numericUpDown -->
<script src="js/formatDatumVreme.js"></script> <!-- funkcije za formatiranje datuma i vremena -->
<script src="js/elementiLeta.js"></script> <!-- funkcije za prikaz delova leta -->

<style>
  .ivica {
    border: 0.4px solid #E0E0E0;
  }
</style>


<div id="alert" class="alert alert-warning" role="alert" style="display: none;">
</div>
<div id="nodata" class="alert alert-info" role="alert" style="display:none;">
  Ne postoji nijedan let sa datim kriterijumima.
</div>


<div class="form-row mr-0 ml-5 mt-5">
  <div class="col-auto mt-2">
    <select class="custom-select mr-sm-2" id="start_city">
      <option value="" disabled selected>Početak</option>
      <?php
      for($i = 0; $i < $duzStart; $i++) {
        echo '<option value='.$startGradovi[$i]->id.'>'.$startGradovi[$i]->ime.'</option>';
      }
      ?>
    </select>
  </div>
  <div class="col-auto mt-2" style="padding-right:2%;">
    <select class="custom-select mr-sm-2" id="end_city">
      <option value="" disabled selected>Kraj</option>
      <?php
      for($i = 0; $i < $duzEnd; $i++) {
        echo '<option value='.$endGradovi[$i]->id.'>'.$endGradovi[$i]->ime.'</option>';
      }
      ?>
    </select>
  </div>

  <div class="col-lg-2 mt-2">
    <div class="input-group number-spinner">
      <span class="input-group-btn">
        <button class="btn btn-default ivica" data-dir="dwn"><i class="fas fa-minus"></i></button>
      </span>
      <input type="text" class="form-control text-center" id="fir" placeholder="first" value="" readonly="true">
      <!--miha -->
      <span class="input-group-btn">
        <button class="btn btn-default ivica" data-dir="up"><i class="fas fa-plus"></i></button>
      </span>
    </div>
  </div>
  <div class="col-lg-2 mt-2">
    <div class="input-group number-spinner">
      <span class="input-group-btn">
        <button class="btn btn-default ivica" data-dir="dwn"><i class="fas fa-minus"></i></button>
      </span>
      <input type="text" class="form-control text-center" id="bus" placeholder="bussiness" value="" readonly="true">
      <span class="input-group-btn">
        <button class="btn btn-default ivica" data-dir="up"><i class="fas fa-plus"></i></button>
      </span>
    </div>
  </div>
  <div class="col-lg-2 mt-2">
    <div class="input-group number-spinner">
      <span class="input-group-btn">
        <button class="btn btn-default ivica" data-dir="dwn"><i class="fas fa-minus"></i></button>
      </span>
      <input type="text" class="form-control text-center" id="eco" placeholder="economy" value="" readonly="true">
      <span class="input-group-btn">
        <button class="btn btn-default ivica" data-dir="up"><i class="fas fa-plus"></i></button>
      </span>
    </div>
  </div>

  <div class="row col-md-3 mt-2">
    <div class="col-md-6 pr-md-0">
      <input class="form-control" type="text" id="datepicker1" placeholder="početni datum">
    </div>
    <div class="col-md-6">
      <input class="form-control" type="text" id="datepicker2" placeholder="krajnji datum">
    </div>
  </div>

  <div class="row col-md-3 mt-2">
    <div class="col-md-6 pr-md-0">
      <input class="form-control" type="text" id="minCena" placeholder="minimalna cena">
    </div>
    <div class="col-md-6">
      <input class="form-control" type="text" id="maxCena" placeholder="maksimalna cena">
    </div>
  </div>

  <div class="col-md-auto mt-2">
    <button class="btn btn-primary" id="posalji">Pretraži</button>
  </div>
  <script>
    $(function () {
      $("#datepicker1").datepicker();
    });
    $(function () {
      $("#datepicker2").datepicker();
    });
  </script>
</div>

<div id="lista" class="list-group mr-0 pr-0 mt-2">
  <div>

    <script>
      function dobar(val) {
        if (val !== "" && val > 0) return true;
        return false;
      }

      function suma(fir, bus, eco) {
        if (fir == "") fir = 0;
        if (bus == "") bus = 0;
        if (eco == "") eco = 0;
        return parseInt(fir) + parseInt(bus) + parseInt(eco);
      }

      function dodajInput(name, value, form) {
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", name);
        input.setAttribute("value", value);
        form.appendChild(input);
        return;
      }

      function dobarFormat(s) {
        var niz = s.split("/");
        return niz[2] + "-" + niz[0] + "-" + niz[1];
      }


      var pocGrad = document.getElementById("start_city");
      var krGrad = document.getElementById("end_city");

      var dugme = document.getElementById("posalji");
      var lista = document.getElementById("lista");
      dugme.addEventListener("click", function () {
        lista.textContent = "";
        var prvi = pocGrad.options[pocGrad.selectedIndex].value;
        var drugi = krGrad.options[krGrad.selectedIndex].value;
        var pocIme = pocGrad.options[pocGrad.selectedIndex].innerHTML;
        var krIme = krGrad.options[krGrad.selectedIndex].innerHTML;
        var minCena = document.getElementById("minCena").value;
        var maxCena = document.getElementById("maxCena").value;
        var fir = document.getElementById("fir").value;
        var bus = document.getElementById("bus").value;
        var eco = document.getElementById("eco").value;
        if (minCena == "") minCena = 0;
        if (maxCena == "") maxCena = Number.MAX_SAFE_INTEGER;
        minCena = parseInt(minCena);
        maxCena = parseInt(maxCena);
        console.log(minCena + " " + maxCena);
        if (!dobar(prvi) || !dobar(drugi) || datepicker1.value == "" || datepicker2.value == "") {
          document.getElementById("alert").style.display = "block";
          document.getElementById("alert").innerHTML = "Nisu popunjena sva obavezna polja!";
        } else if (prvi.localeCompare(drugi) == 0 || prvi === "" || drugi === "") {
          document.getElementById("alert").style.display = "block";
          document.getElementById("alert").innerHTML = "Uneti gradovi su isti!";
        } else if (!porediDatume(datepicker1.value, datepicker2.value)) {
          document.getElementById("alert").style.display = "block";
          document.getElementById("alert").innerHTML = "Krajnji datum je manji od početnog!";
        } else if (isNaN(minCena) || isNaN(maxCena)) {
          document.getElementById("alert").style.display = "block";
          document.getElementById("alert").innerHTML = "Podaci u lošem formatu!";
        } else if (maxCena < minCena) {
          document.getElementById("alert").style.display = "block";
          document.getElementById("alert").innerHTML = "Viša cena je manja od niže!";
        } else if (suma(fir, bus, eco) == 0) {
          document.getElementById("alert").style.display = "block";
          document.getElementById("alert").innerHTML = "Morate izabrati bar jednu kartu.";
        } else {
          document.getElementById("alert").style.display = "none";
          if (!dobar(fir)) fir = 0;
          if (!dobar(bus)) bus = 0;
          if (!dobar(eco)) eco = 0;

          $.post("baza/novaPretraga.php", {
              poc: prvi,
              kr: drugi,
              poslat: true
            },
            function (data, status) {

              var brLetova = 0;
              var letovi = JSON.parse(data);
              console.log(letovi);
              var direktni = letovi.direktni;
              var presedanje = letovi.presedanje;
              if (direktni.length == 0 && presedanje.length == 0) {
                console.log("Nema letova");
              } else {
                document.getElementById("nodata").style.display = "none";
                for (var i = 0; i < direktni.length; i++) {

                  if (!porediDatume(dobarFormat(datepicker1.value), direktni[i].datum) ||
                    !porediDatume(direktni[i].datum, dobarFormat(datepicker2.value))) continue;
                  var cena = fir * direktni[i].firPrice + bus * direktni[i].busPrice + eco * direktni[i].ecoPrice;
                  if (cena > maxCena || cena < minCena) continue;
                  if (fir > direktni[i].firCap || bus > direktni[i].busCap || eco > direktni[i].ecoCap) continue; //provera kapaciteta
                  brLetova++;
                  console.log("Ima direktnih");
                  var form = document.createElement("form");
                  form.setAttribute("method", "post");
                  form.setAttribute("action", "baza/karta.php");
                  form.setAttribute("id", "direktno" + i + "forma");
                  var a = document.createElement("a");
                  a.setAttribute("id", "direktno" + i);
                  a.setAttribute("href", "#d" + i);
                  a.setAttribute("class", "list-group-item list-group-item-action padding:0; margin:0;");

                  var div = document.createElement("div");
                  div.setAttribute("class", "d-flex w-100 justify-content-between");
                  div.setAttribute("style", "pointer-events:none;");

                  var naslov = document.createElement("h4");

                  naslov.innerHTML = "Direktan let(" + pocIme + "-" + krIme + ")<span class='tag'>" + cena +
                    "RSD</span> ";
                  var info = document.createElement("medium");
                  info.setAttribute("style", "font-size:21px;");
                  info.innerHTML = ispisiDatum(direktni[i].datum) + ", " + direktni[i].dan;
                  div.appendChild(naslov);
                  div.appendChild(info);
                  a.appendChild(div);

                  var btnCollapse = document.createElement("button");
                  btnCollapse.setAttribute("class", "btn btn-primary");
                  btnCollapse.setAttribute("type", "button");
                  btnCollapse.setAttribute("data-toggle", "collapse");
                  btnCollapse.setAttribute("data-target", "#collapseDirektno" + i);
                  btnCollapse.setAttribute("aria-expanded", "false");
                  btnCollapse.setAttribute("aria-controls", "collapseExample");
                  btnCollapse.setAttribute("style", "margin-right:1%");
                  btnCollapse.innerHTML = "Detalji";
                  a.appendChild(btnCollapse);

                  var collapse = document.createElement("div");
                  collapse.classList.add("collapse");
                  collapse.setAttribute("id", "collapseDirektno" + i);
                  collapse.setAttribute("style", "pointer-events:none;");

                  if (dobar(fir))
                    slicica(a, "fa-crown", direktni[i].firCap);
                  if (dobar(bus))
                    slicica(a, "fa-briefcase", direktni[i].busCap);
                  if (dobar(eco))
                    slicica(a, "fa-male", direktni[i].ecoCap);

                  var red = document.createElement("div");
                  red.classList.add("row");
                  red.setAttribute("style", "pointer-events:none;");

                  prviGrad(red, ispisi(direktni[i].vreme), pocIme);
                  ispisLet(red, formatMin(direktni[i].vreme_trajanja));
                  drugiGrad(red, ispisi(dodaj(direktni[i].vreme, direktni[i].vreme_trajanja)), krIme);

                  collapse.appendChild(red);
                  a.appendChild(collapse);
                  form.appendChild(a);

                  dodajInput("id_leta1", direktni[i].id_leta, form);
                  dodajInput("poslat", true, form);
                  dodajInput("brGradova", 2, form);
                  dodajInput("id_grad1", prvi, form);
                  dodajInput("id_grad2", drugi, form);
                  dodajInput("cnt_fir", fir, form);
                  dodajInput("cnt_bus", bus, form);
                  dodajInput("cnt_eco", eco, form);
                  dodajInput("price_fir1", direktni[i].firPrice, form);
                  dodajInput("price_bus1", direktni[i].busPrice, form);
                  dodajInput("price_eco1", direktni[i].ecoPrice, form);
                  dodajInput("cena", cena, form)

                  dodajInput("datum1", direktni[i].datum, form);
                  dodajInput("vreme1", ispisi(direktni[i].vreme), form);

                  lista.appendChild(form);
                }
                document.body.appendChild(lista);

                for (var i = 0; i < presedanje.length; i++) {

                  if (!porediDatume(dobarFormat(datepicker1.value), presedanje[i].prvi.datum) ||
                    !porediDatume(presedanje[i].prvi.datum, dobarFormat(datepicker2.value))) continue;

                  var cena = fir * presedanje[i].prvi.firPrice + bus * presedanje[i].prvi.busPrice + eco *
                    presedanje[i].prvi.ecoPrice;
                  cena += fir * presedanje[i].drugi.firPrice + bus * presedanje[i].drugi.busPrice + eco *
                    presedanje[i].drugi.ecoPrice;
                  if (cena > maxCena || cena < minCena) continue;
                  if (fir > presedanje[i].prvi.firCap || bus > presedanje[i].prvi.busCap || eco > presedanje[i].prvi.ecoCap) continue;  //provera kapaciteta
                  if (fir > presedanje[i].drugi.firCap || bus > presedanje[i].drugi.busCap || eco > presedanje[i].drugi.ecoCap) continue; // ---||-------
                  brLetova++;
                  console.log("Ima presedanja");
                  var form = document.createElement("form");
                  form.setAttribute("method", "post");
                  form.setAttribute("action", "baza/karta.php");
                  form.setAttribute("id", "presedanje" + i + "forma");
                  var a = document.createElement("a");
                  a.setAttribute("id", "presedanje" + i);
                  a.setAttribute("type", "submit");
                  a.setAttribute("href", "#p" + i);
                  a.setAttribute("class", "list-group-item list-group-item-action");

                  var div = document.createElement("div");
                  div.setAttribute("class", "d-flex w-100 justify-content-between");
                  div.setAttribute("style", "pointer-events:none;"); ///kad klikcem da mi ne ulazi u njega

                  var naslov = document.createElement("h4");

                  naslov.innerHTML = "Let sa presedanjem(" + pocIme + "-" + presedanje[i].prvi.ime + "-" + krIme +
                    ")<span class='tag'>" + cena + "RSD</span> ";
                  var info = document.createElement("medium");
                  info.setAttribute("style", "font-size:21px;");
                  info.innerHTML = ispisiDatum(presedanje[i].prvi.datum) + ", " + presedanje[i].prvi.dan;
                  div.appendChild(naslov);
                  div.appendChild(info);
                  a.appendChild(div);

                  var btnCollapse = document.createElement("button");
                  btnCollapse.setAttribute("class", "btn btn-primary");
                  btnCollapse.setAttribute("type", "button");
                  btnCollapse.setAttribute("data-toggle", "collapse");
                  btnCollapse.setAttribute("data-target", "#collapsePresedanje" + i);
                  btnCollapse.setAttribute("aria-expanded", "false");
                  btnCollapse.setAttribute("aria-controls", "collapseExample");
                  btnCollapse.setAttribute("style", "margin-right:1%");
                  btnCollapse.innerHTML = "Detalji";
                  a.appendChild(btnCollapse);

                  var collapse = document.createElement("div");
                  collapse.classList.add("collapse");
                  collapse.setAttribute("id", "collapsePresedanje" + i);
                  collapse.setAttribute("style", "pointer-events:none;");

                  if (dobar(fir))
                    slicica(a, "fa-crown", Math.min(presedanje[i].prvi.firCap, presedanje[i].drugi.firCap));
                  if (dobar(bus))
                    slicica(a, "fa-briefcase", Math.min(presedanje[i].prvi.busCap, presedanje[i].drugi.busCap));
                  if (dobar(eco))
                    slicica(a, "fa-male", Math.min(presedanje[i].prvi.ecoCap, presedanje[i].drugi.ecoCap));

                  var diff = razlika(presedanje[i].prvi.datum, presedanje[i].prvi.vreme, presedanje[i].drugi
                    .datum, presedanje[i].drugi.vreme);
                  var vreme_cekanja = oduzmi(diff, presedanje[i].prvi.vreme_trajanja);

                  var red = document.createElement("div");
                  red.setAttribute("class", "row");
                  red.setAttribute("style", "pointer-events:none;"); //kad klikcem da ne ulazi

                  prviGrad(red, ispisi(presedanje[i].prvi.vreme), pocIme);
                  ispisLet(red, formatMin(presedanje[i].prvi.vreme_trajanja));
                  drugiGrad(red, ispisi(dodaj(presedanje[i].prvi.vreme, presedanje[i].prvi.vreme_trajanja)),
                    presedanje[i].prvi.ime);
                  vremeCekanja(red, vreme_cekanja);
                  ispisLet(red, formatMin(presedanje[i].drugi.vreme_trajanja));
                  treciGrad(red, ispisi(dodaj(presedanje[i].drugi.vreme, presedanje[i].drugi.vreme_trajanja)),
                    krIme);

                  collapse.append(red);
                  a.append(collapse);
                  form.append(a);
                  dodajInput("id_leta1", presedanje[i].prvi.id_leta, form);
                  dodajInput("id_leta2", presedanje[i].drugi.id_leta, form);

                  dodajInput("poslat", true, form);
                  dodajInput("brGradova", 3, form);
                  dodajInput("id_grad1", prvi, form);
                  dodajInput("id_grad2", presedanje[i].prvi.id_grada, form);
                  dodajInput("id_grad3", drugi, form);
                  dodajInput("cnt_fir", fir, form);
                  dodajInput("cnt_bus", bus, form);
                  dodajInput("cnt_eco", eco, form);
                  dodajInput("price_fir1", presedanje[i].prvi.firPrice, form);
                  dodajInput("price_bus1", presedanje[i].prvi.busPrice, form);
                  dodajInput("price_eco1", presedanje[i].prvi.ecoPrice, form);
                  dodajInput("price_fir2", presedanje[i].drugi.firPrice, form);
                  dodajInput("price_bus2", presedanje[i].drugi.busPrice, form);
                  dodajInput("price_eco2", presedanje[i].drugi.ecoPrice, form);
                  dodajInput("cena", cena, form);

                  dodajInput("datum1", presedanje[i].prvi.datum, form);
                  dodajInput("datum2", presedanje[i].drugi.datum, form);
                  dodajInput("vreme1", ispisi(presedanje[i].prvi.vreme), form);
                  dodajInput("vreme2", ispisi(presedanje[i].drugi.vreme), form);


                  lista.appendChild(form);
                }
              }
              if (brLetova == 0) document.getElementById("nodata").style.display = "block";
            });
        }
      });


      $('#lista').on('click', 'a', function (e) {
        if (event.target.tagName.toLowerCase() === 'a') {

          /*var brGradova = document.getElementsByName("brGradova");
          var cena = document.getElementsByName("cena");
          console.log(cena + " " + brGradova);*/
          var id = e.target.id;
          var form = document.getElementById(id + "forma");
          form.submit();
        }
      });
    </script>

    <?php
include 'footer.php';
?>