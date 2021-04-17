<?php


function check($conn, $query) {
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $query)) return true;
    return false;
}

// provera da li je korisnik usao preko dugmeta
if(isset($_POST['signup-submit'])){
    
    require 'dbh.php';
    print 'lolcina';

    $username = $_POST['username'];
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $jmbg = $_POST['jmbg'];
    $telefon = $_POST['telefon'];
    $mejl = $_POST['mejl'];
    $lozinka = $_POST['lozinka'];
    $lozinka_copy = $_POST['lozinka-copy'];
    
    $sql = "SELECT username FROM putnici WHERE username=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {   # ako hoce da me hakuju
        header("Location: ../signup.php?error=sqlerror");
        exit();
    }
    else{
        /*mysqli_stmt_bind_param($stmt, "s", $username);     #da imam 2 ? bilo bi {$stmt, "ss", $username, $password};
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $broj_redova = mysqli_stmt_num_rows($stmt);
        if($broj_redova > 0) {
            header("Location: ../signup.php?error=postoji_username");
            exit();
        }*/
        
        $queryU = "SELECT * FROM putnici WHERE username = '$username'";
        $queryM = "SELECT * FROM putnici WHERE mejl = '$mejl'";
        if(!check($conn, $queryU) || !check($conn, $queryM)) {
            header("Location: ../signup.php?error=sqlerror");
            exit();
        }
        $resultU = $conn->query($queryU);
        $resultM = $conn->query($queryM);
        
        if($resultU->num_rows > 0 || $resultM->num_rows > 0) {
            header("Location: ../signup.php?error=exists");
            exit();
        }
        else{
            $sql = "INSERT INTO putnici (username, lozinka, ime, prezime, jmbg, mejl, telefon) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {   # ako hoce da me hakuju 2
                header("Location: ../signup.php?error=sqlerror");
                exit();
            }

            else{
                $hes_lozinka = password_hash($lozinka, PASSWORD_DEFAULT);

                //mysqli_stmt_bind_param($stmt, "sssssss", $username, $lozinka, $ime, $prezime, $jmbg, $mejl, $telefon);
                //mysqli_stmt_execute($stmt);
                //mysqli_stmt_store_result($stmt);
                $query = "INSERT INTO putnici (username, lozinka, ime, prezime, jmbg, mejl, telefon) 
                VALUES('$username', '$hes_lozinka', '$ime', '$prezime', '$jmbg', '$mejl', '$telefon')";
                $result = $conn->query($query);
                
                header("Location: ../login.php?signup=success");
                exit();
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
else{
    header("Location: ../signup.php");
    exit();
}