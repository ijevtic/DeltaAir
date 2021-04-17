<?php

require 'dbh.php';
$startGradovi = array();
$endGradovi = array();
$query = "SELECT DISTINCT ime, id_aerodroma FROM aerodromi JOIN linije ON (id_pocetne_lokacije = id_aerodroma) ORDER BY(ime)";
$result = $conn->query($query);
if($result->num_rows > 0) {

    $duzStart = 0;
    while($row = $result->fetch_assoc()) {
        
        $novi = new stdClass();
        $novi->ime = $row['ime'];
        $novi->id = $row['id_aerodroma'];
        $startGradovi[] = $novi;
        //echo $startGradovi[$duzStart]->ime . " " . $startGradovi[$duzStart]->id;
        $duzStart++;
    }
}

$query = "SELECT DISTINCT ime, id_aerodroma FROM aerodromi JOIN linije ON (id_krajnje_lokacije = id_aerodroma) ORDER BY(ime)";
$result = $conn->query($query);
if($result->num_rows > 0) {

    $duzEnd = 0;
    while($row = $result->fetch_assoc()) {
        
        $novi = new stdClass();
        $novi->ime = $row['ime'];
        $novi->id = $row['id_aerodroma'];
        $endGradovi[] = $novi;
        //echo $endGradovi[$duzEnd]->ime . " " . $endGradovi[$duzEnd]->id;
        $duzEnd++;
    }
}



?>