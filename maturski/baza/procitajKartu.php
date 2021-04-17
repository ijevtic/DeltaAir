<?php

session_start();


function odrediGrad($id_grada, $conn) {
    $query = 'SELECT * FROM aerodromi WHERE id_aerodroma = '.$id_grada.'';
    $result = $conn->query($query);
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $novi = new StdClass();
        $novi->id_grada = $row['id_aerodroma'];
        $novi->ime = $row['ime'];
        $novi->lokacija_x = $row['lokacija_x'];
        $novi->lokacija_y = $row['lokacija_y'];
        return $novi;
    }
}


if (!isset($_SESSION['id_putnika']) || !isset($_GET['poslat'])) {
    header("Location: ../login.php?access=denied");
    exit();
}
if(!isset($_SESSION['kupovina'])) {
    echo json_encode("ne");
}
else {
    require 'dbh.php';
    $novi = $_SESSION['kupovina'];
    unset($_SESSION['kupovina']);
    $novi->id_kupca = $_SESSION['id_putnika'];
    $novi->ime_kupca = $_SESSION['ime'];
    $novi->prezime_kupca = $_SESSION['prezime'];
    $novi->grad1 = odrediGrad($novi->grad1, $conn);
    $novi->grad2 = odrediGrad($novi->grad2, $conn);
    if($novi->broj == 3)
        $novi->grad3 = odrediGrad($novi->grad3, $conn);

    echo json_encode($novi);
}
?>