<?php

function nadji($dan) {
    if($dan == 1) return 'Nedelja';
    if($dan == 2) return 'Ponedeljak';
    if($dan == 3) return 'Utorak';
    if($dan == 4) return 'Sreda';
    if($dan == 5) return 'Četvrtak';
    if($dan == 6) return 'Petak';
    if($dan == 7) return 'Subota';
}

function kapacitet($id_klase, $id_leta, $conn) {
    $query = 'SELECT kapacitet FROM letovi_klase WHERE id_klase = '.$id_klase.' AND id_leta = '.$id_leta.'';
    $result = $conn->query($query);
    if(mysqli_num_rows($result) == 0) return 0;
    if($result->num_rows > 0) {
        if($row = $result->fetch_assoc())
            return $row['kapacitet'];
    }
}

function cena_karte($id_klase, $id_leta, $conn) {
    $query = 'SELECT cena_karte FROM karte WHERE id_klase = '.$id_klase.' AND id_leta = '.$id_leta.'';
    $result = $conn->query($query);
    if(mysqli_num_rows($result) == 0) return 0;
    if($result->num_rows > 0) {
        if($row = $result->fetch_assoc())
            return $row['cena_karte'];
    }
}

function izbroji($id_klase, $id_leta, $conn) {

    $query = 'SELECT id_karte FROM karte WHERE id_klase = '.$id_klase.' AND id_leta = '.$id_leta.'';
    // ako nema karte???
    $result = $conn->query($query);
    if(mysqli_num_rows($result) == 0) return 0;
    if($result->num_rows > 0) {
        if($row = $result->fetch_assoc())
            $id_karte = $row['id_karte'];
    }

    $query = 'SELECT COUNT(*) as broj FROM putnici_karte WHERE id_karte = '.$id_karte.'';
    $result = $conn->query($query);
    if($result->num_rows > 0) {
        if($row = $result->fetch_assoc())
            return $row['broj'];
    }
    return 0;

}

if(isset($_POST['poslat'])){

    require 'dbh.php';

    $direktni = [];
    $presedanje = [];

    $id_poc = $_POST['poc'];
    $id_kr = $_POST['kr'];
    $query = 'SELECT DAYOFWEEK(vreme_polaska) as dan, CAST(vreme_polaska as time) as vreme,
    CAST(vreme_polaska as date) as datum, id_leta, vreme_trajanja FROM letovi JOIN linije USING(id_linije) 
    WHERE id_pocetne_lokacije = '.$id_poc.' AND id_krajnje_lokacije = '.$id_kr.' AND SYSDATE() < vreme_polaska ORDER BY(vreme_polaska)';
    $result = $conn->query($query);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id_leta = $row['id_leta'];
            $novi = new StdClass();
            $novi->id_leta = $id_leta;
            $novi->dan = nadji($row['dan']);
            $novi->datum = $row['datum'];
            $novi->vreme = $row['vreme'];
            $novi->vreme_trajanja = $row['vreme_trajanja'];
            $novi->firPrice = cena_karte(1,$id_leta,$conn);
            $novi->busPrice = cena_karte(2,$id_leta,$conn);
            $novi->ecoPrice = cena_karte(3,$id_leta,$conn);
            $novi->firCap = kapacitet(1,$id_leta,$conn) - izbroji(1,$id_leta,$conn);
            $novi->busCap = kapacitet(2,$id_leta,$conn) - izbroji(2,$id_leta,$conn);
            $novi->ecoCap = kapacitet(3,$id_leta,$conn) - izbroji(3,$id_leta,$conn);
            $direktni[] = $novi;
        }
    }

    $queryPrvi = 'SELECT DAYOFWEEK(vreme_polaska) as dan, CAST(vreme_polaska as time) as vreme,
    CAST(vreme_polaska as date) as datum, id_leta, id_krajnje_lokacije, vreme_polaska, vreme_trajanja, ime FROM letovi JOIN linije USING(id_linije)
    JOIN aerodromi ON(id_aerodroma = id_krajnje_lokacije) 
    WHERE id_pocetne_lokacije = '.$id_poc.' AND SYSDATE() < vreme_polaska ORDER BY(vreme_polaska)';
    $resultPrvi = $conn->query($queryPrvi);
    if($resultPrvi->num_rows > 0) {
        while($rowPrvi = $resultPrvi->fetch_assoc()) {
            
            $id_leta = $rowPrvi['id_leta'];
            $novi = new StdClass();
            $novi->id_leta = $id_leta;
            $novi->id_grada = $rowPrvi['id_krajnje_lokacije'];
            $novi->ime = $rowPrvi['ime'];
            $novi->dan = nadji($rowPrvi['dan']);
            $novi->datum = $rowPrvi['datum'];
            $novi->vreme = $rowPrvi['vreme'];
            $novi->firPrice = cena_karte(1,$id_leta,$conn);
            $novi->busPrice = cena_karte(2,$id_leta,$conn);
            $novi->ecoPrice = cena_karte(3,$id_leta,$conn);
            $novi->firCap = kapacitet(1,$id_leta,$conn) - izbroji(1,$id_leta,$conn);
            $novi->busCap = kapacitet(2,$id_leta,$conn) - izbroji(2,$id_leta,$conn);
            $novi->ecoCap = kapacitet(3,$id_leta,$conn) - izbroji(3,$id_leta,$conn);
            $novi->pravoV = $rowPrvi['vreme_polaska'];
            $novi->vreme_trajanja = $rowPrvi['vreme_trajanja'];

            //$novi->id_poc_drugi = $rowPrvi['id_krajnje_lokacije'];
            //$presedanje[] = $novi;
            $id_pocDrugi = $rowPrvi['id_krajnje_lokacije'];
            $vreme_prvog = $rowPrvi['vreme_polaska'];    #dodaj i prema duzini trajanja
            $queryDrugi = 'SELECT DAYOFWEEK(vreme_polaska) as dan, CAST(vreme_polaska as time) as vreme,
            CAST(vreme_polaska as date) as datum, id_leta, vreme_polaska,vreme_trajanja FROM letovi JOIN linije USING(id_linije)
            WHERE id_pocetne_lokacije = '.$id_pocDrugi.' AND id_krajnje_lokacije = '.$id_kr.'';
            $resultDrugi = $conn->query($queryDrugi);
            if(mysqli_num_rows($resultDrugi) == 0) continue;
            if($resultDrugi->num_rows > 0) {
                while($rowDrugi = $resultDrugi->fetch_assoc()) {

                    if($rowDrugi['vreme_polaska'] < $novi->pravoV) continue;
                    $id_leta2 = $rowDrugi['id_leta'];
                    $novi2 = new StdClass();
                    $novi2->id_leta = $id_leta2;
                    $novi2->vreme_trajanja = $rowDrugi['vreme_trajanja'];
                    $novi2->dan = nadji($rowDrugi['dan']);
                    $novi2->datum = $rowDrugi['datum'];
                    $novi2->vreme = $rowDrugi['vreme'];
                    $novi2->firPrice = cena_karte(1,$id_leta2,$conn);
                    $novi2->busPrice = cena_karte(2,$id_leta2,$conn);
                    $novi2->ecoPrice = cena_karte(3,$id_leta2,$conn);
                    $novi2->firCap = kapacitet(1,$id_leta2,$conn) - izbroji(1,$id_leta2,$conn);
                    $novi2->busCap = kapacitet(2,$id_leta2,$conn) - izbroji(2,$id_leta2,$conn);
                    $novi2->ecoCap = kapacitet(3,$id_leta2,$conn) - izbroji(3,$id_leta2,$conn);

                    $el = new StdClass();
                    $el->prvi = $novi;
                    $el->drugi = $novi2;
                    $presedanje[] = $el;
                }
            }

        }
    }
    $letovi = new StdClass();
    $letovi->direktni = $direktni;
    $letovi->presedanje = $presedanje;
    echo json_encode($letovi);

    /*$niz = [];
    $query = 'SELECT ime, id_aerodroma FROM aerodromi';
    $result = $conn->query($query);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $novi = new StdClass();
            $novi->id_aerodroma = $row['id_aerodroma'];
            $novi->ime = $row['ime'];
            $niz[] = $novi;
        }
    }
    echo json_encode($niz);*/
    
}