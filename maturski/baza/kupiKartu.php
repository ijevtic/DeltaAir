<?php

function nadji($id_klase, $id_leta, $conn) {
    $query = 'SELECT id_karte FROM karte WHERE id_klase='.$id_klase.' AND id_leta='.$id_leta.'';
    $result = $conn->query($query);
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id_karte'];
    }
}

function upisi($id_klase, $kol, $id_putnika, $id_leta, $conn) {

    $id_karte = nadji($id_klase, $id_leta, $conn);

    for($i = 0; $i < $kol; $i++) {
        $query = "INSERT INTO putnici_karte (id_putnika, id_karte) 
        VALUES('$id_putnika', '$id_karte')";
        $result = $conn->query($query);
    }
}

session_start();

if (!isset($_SESSION['id_putnika']) || !isset($_POST['poslat'])) {
    header("Location: ../login.php?access=denied");
    exit();
}
require 'dbh.php';

$broj = $_POST['broj'];
$id_putnika = $_POST['id_putnika'];
$id_leta1 = $_POST['id_leta1'];
$id_leta2 = $_POST['id_leta2'];
$fir = (int)$_POST['cnt_fir'];
$bus = (int)$_POST['cnt_bus'];
$eco = (int)$_POST['cnt_eco'];

if($fir > 0) upisi(1, $fir, $id_putnika, $id_leta1, $conn);
if($bus > 0) upisi(2, $bus, $id_putnika, $id_leta1, $conn);
if($eco > 0) upisi(3, $eco, $id_putnika, $id_leta1, $conn);

if($broj == 3) {
    if($fir > 0) upisi(1, $fir, $id_putnika, $id_leta2, $conn);
    if($bus > 0) upisi(2, $bus, $id_putnika, $id_leta2, $conn);
    if($eco > 0) upisi(3, $eco, $id_putnika, $id_leta2, $conn);

}

$ok = true;
echo json_encode($ok);

?>