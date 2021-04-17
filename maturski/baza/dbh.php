<?php
$servername = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "maturski";
$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
if(!$conn){
    die("Nije uspelo povezivanje sa bazom: ".mysqli_connect_error());
}