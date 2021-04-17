<?php

function ubaci($id, $ime) {
    $novi = new StdClass();
    $novi->id = $id;
    $novi->ime = $ime;
    return $novi;
}

session_start();

if(!isset($_POST['poslat']) || !isset($_SESSION['id_putnika']) || $_SESSION['ime'] != 'admin')
    exit();

require 'dbh.php';

$niz = [];

$query = 'SELECT ime, id_aerodroma FROM aerodromi';
$result = $conn->query($query);
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $niz[] = ubaci($row['id_aerodroma'], $row['ime']);
    }
}

$linije = [];
$query = 'SELECT * FROM linije';
$result = $conn->query($query);
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        
        $id1 = $row['id_pocetne_lokacije'];
        $id2 = $row['id_krajnje_lokacije'];
        $novi = new StdClass();
        $novi->id1 = $id1;
        $novi->id2 = $id2;
        $novi->idLinije = $row['id_linije'];
        for($i = 0; $i < count($niz); $i++) {
            if($niz[$i]->id == $id1) $novi->ime1 = $niz[$i]->ime;
            if($niz[$i]->id == $id2) $novi->ime2 = $niz[$i]->ime;
        }
        $linije[] = $novi;
    }
}
$pocetniGradovi = [];
$query = 'SELECT DISTINCT(id_pocetne_lokacije), ime FROM linije JOIN aerodromi ON (id_aerodroma=id_pocetne_lokacije)';
$result = $conn->query($query);
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $novi = new StdClass();
        $novi->id = $row['id_pocetne_lokacije'];
        $novi->ime = $row['ime'];
        $pocetniGradovi[] = $novi;
    }
}
$obj = new StdClass();
$obj->niz = $niz;
$obj->linije = $linije;
$obj->pocetniGradovi = $pocetniGradovi;
echo json_encode($obj);

?>






