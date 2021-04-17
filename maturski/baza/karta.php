<?php

function dodeli1($k, $c) {
    $val = new StdClass();
    $val->kol = $k;
    $val->cena1 = $c;
    return $val;
}

function dodeli2($k, $c1, $c2) {
    $val = new StdClass();
    $val->kol = $k;
    $val->cena1 = $c1;
    $val->cena2 = $c2;
    return $val;
}

function napravi($id_leta, $datum, $vreme) {
    $novi = new StdClass();
    $novi->id_leta = $id_leta;
    $novi->datum = $datum;
    $novi->vreme = $vreme;
    return $novi;
}

session_start();

if (!isset($_SESSION['id_putnika']) || !isset($_POST['poslat'])) {
    header("Location: ../login.php?access=denied");
    exit();
}

$novi = new StdClass();
$novi->broj = $_POST['brGradova'];
$novi->grad1 = $_POST['id_grad1'];
$novi->grad2 = $_POST['id_grad2'];
$novi->cena = $_POST['cena'];
$novi->let1 = napravi($_POST['id_leta1'], $_POST['datum1'], $_POST['vreme1']);
if($_POST['brGradova'] == 2) {
    $novi->fir = dodeli1($_POST['cnt_fir'], $_POST['price_fir1']);
    $novi->bus = dodeli1($_POST['cnt_bus'], $_POST['price_bus1']);
    $novi->eco = dodeli1($_POST['cnt_eco'], $_POST['price_eco1']);
}
else {
    $novi->grad3 = $_POST['id_grad3'];
    $novi->let2 = napravi($_POST['id_leta2'], $_POST['datum2'], $_POST['vreme2']);
    $novi->fir = dodeli2($_POST['cnt_fir'], $_POST['price_fir1'], $_POST['price_fir2']);
    $novi->bus = dodeli2($_POST['cnt_bus'], $_POST['price_bus1'], $_POST['price_bus2']);
    $novi->eco = dodeli2($_POST['cnt_eco'], $_POST['price_eco1'], $_POST['price_eco2']);
}

$_SESSION['kupovina'] = $novi;
header("Location: ../kupovina.php");
exit();
//unset($_SESSION['kupovina']);

?>






