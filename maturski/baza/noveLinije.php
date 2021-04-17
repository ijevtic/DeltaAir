<?php

function vratiGradove($id_pocetka, $conn) {
    $query = "SELECT id_aerodroma, ime, lokacija_x, lokacija_y, opis
    FROM aerodromi JOIN linije ON(id_aerodroma = id_krajnje_lokacije) 
    WHERE id_pocetne_lokacije = $id_pocetka";
    $result = $conn->query($query);
    $niz = [];
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $novi = new StdClass();
            $novi->id = $row['id_aerodroma'];
            $novi->ime = $row['ime'];
            $novi->lokacija_x = $row['lokacija_x'];
            $novi->lokacija_y = $row['lokacija_y'];
            $novi->opis = $row['opis'];
            $niz[] = $novi;
        }
        return $niz;
    }
}



if(isset($_GET['pocetak'])) {

    require 'dbh.php';
    $id_pocetka = $_GET['pocetak'];
    $lista = vratiGradove($id_pocetka, $conn);
    echo json_encode($lista);
}

?>