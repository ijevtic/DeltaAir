<?php

if(isset($_POST['login-submit'])){

    //print 'lolcina';
    require 'dbh.php';
    $username = $_POST['username'];
    $lozinka = $_POST['lozinka'];

    if(empty($username) || empty($lozinka)) {
        header("Location: ../login.php?error=emptyfields");
        exit();
    }
    else {
         $sql = "SELECT * FROM putnici WHERE username=? OR mejl=?";  #logovanje preko mejla ili username
         $stmt = mysqli_stmt_init($conn);
         if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../login.php?error=sqlerror");
            exit();
         }

         else {
             mysqli_stmt_bind_param($stmt, "ss", $username, $username);
             mysqli_stmt_execute($stmt);
             $result = mysqli_stmt_get_result($stmt);
             if($row = mysqli_fetch_assoc($result)) {
                 $lozinka_provera = password_verify($lozinka, $row['lozinka']);
                 if($lozinka_provera == false) {
                    header("Location: ../login.php?error=wrongpass");
                    exit();
                 }
                 else if($lozinka_provera == true) {
                    
                    session_start();
                    $_SESSION['id_putnika'] = $row['id_putnika'];
                    $_SESSION['ime'] = $row['ime'];
                    $_SESSION['prezime'] = $row['prezime'];
                    $_SESSION['mejl'] = $row['mejl'];
                    $_SESSION['telefon'] = $row['telefon'];

                    header("Location: ../login.php?login=success");
                    exit();
                 }
                 else {
                    header("Location: ../login.php?error=wrongpass");
                    exit();
                 }
             }
             else {
                header("Location: ../login.php?error=nouser");
                exit();
             }
         }
    }
}
else{
    header("Location ../login.php");
    exit();
}