<?php
require "header.php";

?>
<script src="js/formatDatumVreme.js"></script>
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-let-tab" data-toggle="tab" href="#nav-let" role="tab"
            aria-controls="nav-let" aria-selected="true">Dodaj novi let</a>
        <a class="nav-item nav-link" id="nav-linija-tab" data-toggle="tab" href="#nav-linija" role="tab"
            aria-controls="nav-linija" aria-selected="false">Dodaj novu liniju</a>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-let" role="tabpanel" aria-labelledby="nav-let-tab">
        <div id="alert1" class="alert alert-warning" role="alert" style="display: none;"></div>
        <div id="success1" class="alert alert-success" role="alert" style="display: none;"></div>
        <div class="row align-items-center mr-0 ml-5 mt-5">
            <div class="col-md-2 mt-2">
                <select class="custom-select mr-sm-2" id="let_poc">
                    <option value="" disabled selected>Izaberi početni grad</option>
                </select>
            </div>
            <div class="col-md-2 mt-2">
                <select class="custom-select mr-sm-2" id="let_kr" disabled>
                    <option value="" disabled selected>Izaberi krajnji grad</option>
                </select>
            </div>
        </div>
        <div class="row align-items-center mr-0 ml-5 mt-4">
            <div class="col-lg-2 mt-2">
                <label class="label label-default">Datum poletanja</label>
                <input class="form-control" type="text" id="datepicker" placeholder="">
            </div>
            <div class="col-lg-2 mt-2">
                <label class="label label-default">Vreme poletanja</label>
                <input type="time" class="form-control" id="appt" name="appt" placeholder="vreme">
            </div>
        </div>
        <div class="row align-items-center mr-0 ml-5 mt-4">
            <div class="col-lg-2 mt-2">
                <label class="label label-default">Prva klasa</label>
                <input class="form-control" id="cnt_fir" type="text" placeholder="Broj mesta">
            </div>
            <div class="col-lg-2 mt-2">
                <label class="label label-default">Biznis klasa</label>
                <input class="form-control" id="cnt_bus" type="text" placeholder="Broj mesta">
            </div>
            <div class="col-lg-2 mt-2">
                <label class="label label-default">Ekonomska klasa</label>
                <input class="form-control" id="cnt_eco" type="text" placeholder="Broj mesta">
            </div>
        </div>
        <div class="row align-items-center mr-0 ml-5 mt-2">
            <div class="col-lg-2 mt-2">
                <label class="label label-default">Prva klasa</label>
                <input class="form-control" id="price_fir" type="text" placeholder="Cena karte">
            </div>
            <div class="col-lg-2 mt-2">
                <label class="label label-default">Biznis klasa</label>
                <input class="form-control" id="price_bus" type="text" placeholder="Cena karte">
            </div>
            <div class="col-lg-2 mt-2">
                <label class="label label-default">Ekonomska klasa</label>
                <input class="form-control" id="price_eco" type="text" placeholder="Cena karte">
            </div>
        </div>
        <div class="row align-items-center mr-0 ml-5 mt-3">
            <div class="col-sm-1 mt-2">
                <button id="submitLet" class="btn btn-primary">Dodaj let</button>
            </div>
        </div>


    </div>
    <div class="tab-pane fade" id="nav-linija" role="tabpanel" aria-labelledby="nav-linija-tab">
        <div id="alert2" class="alert alert-warning" role="alert" style="display: none;"></div>
        <div id="success2" class="alert alert-success" role="alert" style="display: none;"></div>
        <div class="row align-items-center mr-0 ml-5 mt-5">
            <div class="col-md-2 mt-2">
                <select class="custom-select mr-sm-2" id="start_city">
                    <option value="" disabled selected>Početni grad</option>
                </select>
            </div>
            <div class="col-md-2 mt-2">
                <select class="custom-select mr-sm-2" id="end_city">
                    <option value="" disabled selected>Krajnji grad</option>
                </select>
            </div>
            <div class="col-md-2 mt-2">
                <input id="vreme" type="text" class="form-control" placeholder="Dužina leta(min)">
            </div>
            <div class="col-md-2 mt-2">
                <button id="submitLiniju" class="btn btn-primary">Dodaj</button>
            </div>
        </div>
    </div>
</div>

<script>
    var time = document.getElementById("appt");

    $(function () {
        $("#datepicker").datepicker();
    });

    function opcija(grad, cont) {
        var opt = document.createElement("option");
        opt.setAttribute("value", grad.id);
        opt.innerHTML = grad.ime;
        cont.appendChild(opt);
        return;
    }

    function dobar(val) {
        if (val !== "" && val > 0) return true;
        return false;
    }

    function valid(cnt, price) {
        console.log(cnt + " " + price);
        if (isNaN(cnt) || isNaN(price)) return false;
        if (cnt > 0 && price <= 0) return false;
        return true;
    }

    function danasnji_datum() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();
        today = yyyy + '/' + mm + '/' + dd;
        return today;
    }

    function formatirajDatumAdmin(d) {
        var n = d.split("/");
        return n[2] + "-" + n[0] + "-" + n[1];
    }

    var listaPoc = document.getElementById("start_city");
    var listaKr = document.getElementById("end_city");
    var letPoc = document.getElementById("let_poc");
    var letKr = document.getElementById("let_kr");
    var gradovi = [];
    var linije = [];

    function ucitavajAdmin() {
        $.post("baza/listaGradova.php", {
                poslat: true
            },
            function (data, status) {
                listaPoc.innerHTML = "<option value='' disabled selected>Početni grad</option>";
                var obj = JSON.parse(data);

                gradovi = obj.niz;
                for (var i = 0; i < gradovi.length; i++) {
                    opcija(gradovi[i], listaPoc);
                    opcija(gradovi[i], listaKr);
                }
                var pocetniGradovi = obj.pocetniGradovi;
                for (var i = 0; i < pocetniGradovi.length; i++) {
                    opcija(pocetniGradovi[i], letPoc);
                }
                linije = obj.linije;
            });
    }

    letPoc.addEventListener("change", function () {
        letKr.innerHTML = "<option value='' disabled selected>Krajnji grad</option>";
        letKr.disabled = false;
        var idPoc = parseInt(letPoc.options[letPoc.selectedIndex].value);
        for (var i = 0; i < linije.length; i++) {
            if (parseInt(linije[i].id1) == idPoc) {
                opcija({
                    id: linije[i].idLinije,
                    ime: linije[i].ime2
                }, letKr);
            }
        }
    })

    var pocGrad = document.getElementById("start_city");
    var krGrad = document.getElementById("end_city");
    var cnt_fir = document.getElementById("cnt_fir");
    var cnt_bus = document.getElementById("cnt_bus");
    var cnt_eco = document.getElementById("cnt_eco");
    var price_fir = document.getElementById("price_fir");
    var price_bus = document.getElementById("price_bus");
    var price_eco = document.getElementById("price_eco");


    document.getElementById("submitLet").addEventListener("click", function () {
        var id1 = letPoc.options[letPoc.selectedIndex].value;
        var id2 = letKr.options[letKr.selectedIndex].value;
        //console.log(formatirajDatumAdmin(datepicker.value));
        if (id1 == "" || id2 == "" || time.value == "" || datepicker.value == "" || !valid(cnt_fir.value,
                price_fir.value) ||
            !valid(cnt_bus.value, price_bus.value) || !valid(cnt_eco.value, price_eco.value)) {
            document.getElementById("alert1").style.display = "block";
            document.getElementById("alert1").innerHTML = "Podaci su u lošem formatu!";
        } else if (!porediDatume(danasnji_datum(), datepicker.value)) {
            document.getElementById("alert1").style.display = "block";
            document.getElementById("alert1").innerHTML = "Datum nije važeći!";
        } else {
            $.post("baza/dodajAdmin.php", {
                    cnt_fir: cnt_fir.value,
                    cnt_bus: cnt_bus.value,
                    cnt_eco: cnt_eco.value,
                    price_fir: price_fir.value,
                    price_bus: price_bus.value,
                    price_eco: price_eco.value,
                    id_linije: letKr.options[letKr.selectedIndex].value,
                    datum: formatirajDatumAdmin(datepicker.value),
                    vreme: time.value,
                    tip: "let",
                    poslat: true
                },
                function (data, status) {
                    console.log(data);
                    if (JSON.parse(data) == "-1") {
                        document.getElementById("alert1").style.display = "block";
                        document.getElementById("alert1").innerHTML = "Linija već postoji!";
                    } else {
                        document.getElementById("alert1").style.display = "none";
                        document.getElementById("success1").style.display = "block";
                        document.getElementById("success1").innerHTML = "Uspešno kreiranje leta!";
                    }
                });


        }
    });

    document.getElementById("submitLiniju").addEventListener("click", function () {
        var prvi = pocGrad.options[pocGrad.selectedIndex].value;
        var drugi = krGrad.options[krGrad.selectedIndex].value;
        var vreme = document.getElementById("vreme").value;
        if (!dobar(prvi) || !dobar(drugi) || vreme == "") {
            document.getElementById("alert2").style.display = "block";
            document.getElementById("alert2").innerHTML = "Nisu popunjena sva obavezna polja!";
        } else if (isNaN(vreme)) {
            document.getElementById("alert2").style.display = "block";
            document.getElementById("alert2").innerHTML = "Podaci su u lošem formatu!";
        } else if (prvi == drugi) {
            document.getElementById("alert2").style.display = "block";
            document.getElementById("alert2").innerHTML = "Početni i krajnji grad su isti!";
        } else {
            $.post("baza/dodajAdmin.php", {
                    vreme: vreme,
                    poc: prvi,
                    kr: drugi,
                    tip: "linija",
                    poslat: true
                },
                function (data, status) {
                    console.log(data);
                    if (JSON.parse(data) == "-1") {
                        document.getElementById("alert2").style.display = "block";
                        document.getElementById("alert2").innerHTML = "Linija već postoji!";
                    } else {
                        document.getElementById("alert2").style.display = "none";
                        document.getElementById("success2").style.display = "block";
                        document.getElementById("success2").innerHTML = "Uspešno kreiranje linije!";
                    }
                });
        }
    });
</script>

<?php
require 'footer.php'
?>