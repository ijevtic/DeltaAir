<?php

if(isset($_GET['citaj'])) { #kad se udje na destinacije

    require 'dbh.php';
    $gradovi = [];

    
    $query = "SELECT DISTINCT(id_aerodroma) as id_aerodroma, ime, lokacija_x, lokacija_y FROM linije 
    JOIN aerodromi ON(id_aerodroma = id_pocetne_lokacije)";
    $result = $conn->query($query);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $novi = new StdClass();
            $novi->id = $row['id_aerodroma'];
            $novi->ime = $row['ime'];
            $novi->lokacija_x = $row['lokacija_x'];
            $novi->lokacija_y = $row['lokacija_y'];
            $gradovi[] = $novi;
        }
        echo json_encode($gradovi);
    }
}
?>