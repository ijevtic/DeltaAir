<?php

include 'header.php';

?>
<script src="js/formatDatumVreme.js"></script> <!-- funkcije za formatiranje datuma i vremena -->
<style>
    .info:hover {
        color: #2398D8;
    }

    .cont {
        white-space: nowrap;
        display: inline-block;
    }
</style>

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-past-tab" data-toggle="tab" href="#nav-past" role="tab"
            aria-controls="nav-past" aria-selected="true">Prošli letovi</a>
        <a class="nav-item nav-link" id="nav-future-tab" data-toggle="tab" href="#nav-future" role="tab"
            aria-controls="nav-future" aria-selected="false">Predstojeći letovi</a>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-past" role="tabpanel" aria-labelledby="nav-past-tab">
        <div id="past" class="row align-items-start mr-0 ml-0">
        </div>
    </div>
    <div class="tab-pane fade" id="nav-future" role="tabpanel" aria-labelledby="nav-future-tab">
        <div id="future" class="row align-items-start mr-0 ml-0">
        </div>
    </div>
</div>


<script>
    function iconDropdown(ime, d) {
        var i = document.createElement("i");
        i.setAttribute("data-toggle", "collapse");
        i.setAttribute("href", "#" + ime);
        i.setAttribute("role", "button");
        i.setAttribute("aria-expanded", "false");
        i.setAttribute("aria-controls", "collapseExample");
        i.setAttribute("class", "fas fa-info-circle fa-x info");
        i.setAttribute("style", "cursor: pointer;");
        d.appendChild(i);
        return;
    }

    function dodajKlasu(text, val, lista) {
        if (val.kol == 0) return;
        var li = document.createElement("ol");
        li.setAttribute("class", "list-group-item");
        li.innerHTML = text + val.kol;
        lista.appendChild(li);
        return;
    }

    function pravi(cnt, boja) {
        var s = "";
        for (var i = 0; i < cnt; i++) {
            s += '<i class="fas fa-dollar-sign" style=" color:' + boja + ';"></i> ';
        }
        return s;
    }

    function racunaj(cena) {
        var boja = "";
        if (cena <= 50000) return pravi(1, "#28CF05");
        else if (cena <= 120000) return pravi(2, "#CFCB00");
        return pravi(3, "#E40000");
    }

    function izracunajCenu(v1, v2, v3) {
        return parseFloat(v1.kol) * parseFloat(v1.cena) +
            parseFloat(v2.kol) * parseFloat(v2.cena) +
            parseFloat(v3.kol) * parseFloat(v3.cena);
    }

    function praviKarticu(val, str, i, row) {
        cena = izracunajCenu(val.fir, val.bus, val.eco);
        var card = document.createElement("div");
        card.setAttribute("class", "card cont");
        card.setAttribute("style", "width: 18rem;");

        var header = document.createElement("div");
        header.setAttribute("class", "card-header");
        header.innerHTML = racunaj(cena);
        card.appendChild(header);

        var cardBody = document.createElement("div");
        cardBody.setAttribute("class", "card-body");

        var cardTitle = document.createElement("h5");
        cardTitle.setAttribute("class", "card-title");
        cardTitle.setAttribute("style", "font-family: 'Jost', serif;");
        cardTitle.innerHTML = val.pocetniGrad + "-" + val.krajnjiGrad;
        iconDropdown(str + i, cardTitle);
        cardBody.appendChild(cardTitle);

        var datum = document.createElement("h6");
        datum.setAttribute("class", "card-subtitle mb-2 text-muted");
        datum.innerHTML = ispisiDatum(val.datum) + ".";
        cardBody.appendChild(datum);

        card.appendChild(cardBody);

        var collapse = document.createElement("div");
        collapse.setAttribute("class", "collapse");
        collapse.id = str + i;

        var lista = document.createElement("ul");
        lista.setAttribute("class", "list-group list-group-flush");
        dodajKlasu("Prva klasa: ", val.fir, lista);
        dodajKlasu("Biznis klasa: ", val.bus, lista);
        dodajKlasu("Ekonomska klasa: ", val.eco, lista);
        collapse.appendChild(lista);
        card.appendChild(collapse);
        var cenaL = document.createElement("ul");
        cenaL.setAttribute("class", "list-group list-group-flush");
        cenaL.setAttribute("style", "font-family: 'Jost', serif;")
        cenaL.innerHTML = '<li class="list-group-item">' + cena + "RSD" + '</li>'
        card.appendChild(cenaL);
        row.appendChild(card);
    }

    var past = [];
    var future = [];
    var rowPast = document.getElementById("past");
    var rowFuture = document.getElementById("future");
    var cena;
    $.post("baza/letoviProfil.php", {
            poslat: true
        },
        function (data, status) {
            rowPast.textContent = "";
            rowFuture.textContent = "";
            console.log(data);
            var s = JSON.parse(data);
            past = s.past;
            future = s.future;
            console.log(s.vreme);
            for (var i = 0; i < past.length; i++) praviKarticu(past[i], "past", i, rowPast);
            for (var i = 0; i < future.length; i++) praviKarticu(future[i], "future", i, rowFuture);

        });
</script>



<?php

include 'footer.php';
?>