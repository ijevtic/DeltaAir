<?php

function nadji($conn, $id_poc, $id_kr) {
    $query = 'SELECT * FROM linije WHERE id_pocetne_lokacije = '.$id_poc.' AND id_krajnje_lokacije = '.$id_kr.'';
    $result = $conn->query($query);
    if(mysqli_num_rows($result) == 0) return false;
    return true;
}

function dodajLiniju($conn, $id_poc, $id_kr, $vreme){
    if(nadji($conn, $id_poc, $id_kr)) {
        echo json_encode("-1");
    }
    else {
        $query = "INSERT INTO linije (id_pocetne_lokacije, id_krajnje_lokacije, vreme_trajanja) 
        VALUES('$id_poc', '$id_kr', '$vreme')";
        $result = $conn->query($query);
        echo json_encode("0");
    }

}

function napravi($cena, $kapacitet) {
    $novi = new StdClass();
    $novi->cena = $cena;
    $novi->kapacitet = $kapacitet;
    return $novi;
}

function dodajKapacitet($conn, $id_leta, $id_klase, $kapacitet) {
    $query = "INSERT INTO letovi_klase (id_leta, id_klase, kapacitet) VALUES('$id_leta', '$id_klase', '$kapacitet')";
    $result = $conn->query($query);
}

function dodajKartu($conn, $id_leta, $id_klase, $cena) {
    $query = "INSERT INTO karte (id_leta, id_klase, cena_karte) VALUES('$id_leta', '$id_klase', '$cena')";
    $result = $conn->query($query);
}
function dodajZaKlasu($conn, $id_leta, $val, $id_klase) {
    dodajKartu($conn, $id_leta, $id_klase, $val->cena);
    dodajKapacitet($conn, $id_leta, $id_klase, $val->kapacitet);
}

function dodajLet($conn, $id_linije, $fir, $bus, $eco, $datum) {
    $query = "INSERT INTO letovi (vreme_polaska, id_linije) VALUES('$datum', '$id_linije')";
    $result = $conn->query($query);
    $id_leta = mysqli_insert_id($conn);
    dodajZaKlasu($conn, $id_leta, $fir, 1);
    dodajZaKlasu($conn, $id_leta, $bus, 2);
    dodajZaKlasu($conn, $id_leta, $eco, 3);
}



session_start();

if(!isset($_POST['poslat']) || !isset($_SESSION['id_putnika']))
    exit();

require 'dbh.php';

$tip = $_POST['tip'];
if($tip == 'linija') dodajLiniju($conn, $_POST['poc'], $_POST['kr'], $_POST['vreme']);
else {
    $format = 'Y-m-d H:i:s';
    $time = $_POST['datum'] . " " . $_POST['vreme'] . ":00";
    $datum = $time;
    $fir = napravi($_POST['price_fir'], $_POST['cnt_fir']);
    $bus = napravi($_POST['price_bus'], $_POST['cnt_bus']);
    $eco = napravi($_POST['price_eco'], $_POST['cnt_eco']);
    dodajLet($conn, $_POST['id_linije'], $fir, $bus, $eco, $datum);
    echo json_encode("0");
}

?>