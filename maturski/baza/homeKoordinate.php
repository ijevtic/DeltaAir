<?php

function vratiPodatke($imeGrada, $conn) {
    $query = 'SELECT * FROM aerodromi WHERE ime = "Beograd"';
    $result = $conn->query($query);
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $novi = new StdClass();
        $novi->id = $row['id_aerodroma'];
        $novi->ime = $row['ime'];
        $novi->x = $row['lokacija_x'];
        $novi->y = $row['lokacija_y'];
        return $novi;
    }
}

function nadji($id_grada, $conn) {
    $query = 'SELECT lokacija_x, lokacija_y FROM aerodromi JOIN linije ON(id_aerodroma = id_krajnje_lokacije) WHERE id_pocetne_lokacije = '.$id_grada.'';
    $result = $conn->query($query);
    $niz = [];
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $novi = new StdClass();
            $novi->x = $row['lokacija_x'];
            $novi->y = $row['lokacija_y'];
            $niz[] = $novi;
        }
    }
    return $niz;
}

if(!isset($_POST['poslat'])) {
    //echo "AAAAAAAAAAAAAAAaa";
    die();
}

require 'dbh.php';

$imeGrada = json_decode($_POST['poc']);

$poc_grad = vratiPodatke($imeGrada, $conn);

$nizKoor = nadji($poc_grad->id, $conn);

$novi = new StdClass();
$novi->nizKoor = $nizKoor;
$novi->poc_grad = $poc_grad;

echo json_encode($novi);

?>