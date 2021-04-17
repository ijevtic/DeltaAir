<?php

function mapaGradova($conn) {
    $niz = [];
    $query = 'SELECT ime, id_aerodroma FROM aerodromi';
    $result = $conn->query($query);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $novi = new StdClass();
            $id = $row['id_aerodroma'];
            $novi->id = $id;
            $novi->ime = $row['ime'];
            $niz[] = $novi;
        }
        return $niz;
    }
}

function nadjiGrad($val, $niz) {
    for($i = 0; $i < sizeof($niz); $i++) {
        if($niz[$i]->id == $val) return $niz[$i]->ime;
    }
}

function nadjiKartu($conn, $id_leta, $id_klase) {
    $query = 'SELECT id_karte, cena_karte FROM karte WHERE id_leta = '.$id_leta.' AND id_klase = '.$id_klase.'';
    $result = $conn->query($query);
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $novi = new StdClass();
        $novi->id = $row['id_karte'];
        $novi->cena = $row['cena_karte'];
        return $novi;
    }
}

function izbrojiKlasu($conn, $id_putnika, $id_leta, $id_klase) {
    $s = nadjiKartu($conn, $id_leta, $id_klase);
    $novi = new StdClass();
    $id_karte = $s->id;
    $novi->cena = $s->cena;
    $query = 'SELECT COUNT(*) AS val FROM putnici_karte WHERE id_karte = '.$id_karte.' AND id_putnika = '.$id_putnika.'';
    $result = $conn->query($query);
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $novi->kol = $row['val'];
        return $novi;
    }
}

session_start();

if (!isset($_SESSION['id_putnika']) || !isset($_POST['poslat'])) {
    header("Location: ../login.php?access=denied");
    exit();
}

require 'dbh.php';

$id_putnika = $_SESSION['id_putnika'];

$mapaGradova = mapaGradova($conn);
$past = [];
$future = [];
date_default_timezone_set('Europe/Belgrade');
$trenutno_vreme = date("Y-m-d");

$query = 'SELECT DISTINCT(id_leta), CAST(vreme_polaska as date) as datum, vreme_polaska 
FROM putnici_karte JOIN karte USING (id_karte) JOIN letovi USING (id_leta)
WHERE id_putnika = '.$id_putnika.' ORDER BY(vreme_polaska)';
$result = $conn->query($query);
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $id_leta = $row['id_leta'];
        
        $queryGradovi = 'SELECT id_pocetne_lokacije, id_krajnje_lokacije FROM linije JOIN letovi USING (id_linije) 
        WHERE id_leta = '.$id_leta.'';
        $resultGradovi = $conn->query($queryGradovi);
        if($resultGradovi->num_rows > 0){
                $rowGradovi = $resultGradovi->fetch_assoc();
                $novi = new StdClass();
                $p = $rowGradovi['id_pocetne_lokacije'];
                $q = $rowGradovi['id_krajnje_lokacije'];
                $novi->pocetniGrad = nadjiGrad($p, $mapaGradova);
                $novi->krajnjiGrad = nadjiGrad($q, $mapaGradova);
                $novi->fir = izbrojiKlasu($conn, $id_putnika, $id_leta, "1");
                $novi->bus = izbrojiKlasu($conn, $id_putnika, $id_leta, "2");
                $novi->eco = izbrojiKlasu($conn, $id_putnika, $id_leta, "3");
                $novi->datum = $row['datum'];
                if($row['vreme_polaska'] < $trenutno_vreme)
                    $past[] = $novi;
                else
                    $future[] = $novi;
        }
        //$niz[] = $row['id_leta'];
    }
}
$niz = new StdClass();
$niz->past = $past;
$niz->future = $future;
$niz->vreme = $trenutno_vreme;
echo json_encode($niz);

?>