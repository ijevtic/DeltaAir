<?php
session_start();

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Delta Air</title>

  <link rel="stylesheet" type="text/css" href="dizajn/cena.css">
  <link rel="stylesheet" type="text/css" href="dizajn/fajl.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
  <!--ikonice-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="dizajn/loader.css" />
  </script>
</head>
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400&display=swap" rel="stylesheet">

<body>

  <body onload="radi()">

    <style>
      html {
        height: 100%;
        margin: 0;
        padding: 0;
      }

      body {
        width: 100%;
        height: 100%;
        background: rgb(228, 239, 255);
        background: linear-gradient(90deg, rgba(228, 239, 255, 1) 0%, rgba(245, 245, 245, 1) 50%, rgba(228, 239, 255, 1) 100%);
        /*background-image: url('slike/pozadina.jpg');
    background-size: cover;*/
      }
    </style>
    <script>
    function inputPoc(klasa,name,type,text,cont) {
      var d = document.createElement("input");
      d.setAttribute("class",klasa);
      d.setAttribute("name", name);
      d.setAttribute("type", type);
      d.setAttribute("placeholder", text);
      cont.appendChild(d);
    }
    function dodajNav(klasa, id, aKlasa, href, tekst, cont) {
      var li = document.createElement("li");
      li.setAttribute("class", klasa);
      if(id != false) li.setAttribute("id", id);
      var a = document.createElement("a");
      a.setAttribute("class", aKlasa);
      a.setAttribute("href", href);
      a.innerHTML = tekst;
      li.appendChild(a);
      cont.appendChild(li);
    }
    </script>

    <header>


      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
          <img src="slike/logo.png" class="bez_pozadine" alt="logo" style="width:35px;">
        </a>
        <a class="navbar-brand" href="#" style="font-family:'Jost', serif; font-weight:300;">Delta Air</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02"
          aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">

          <ul class="navbar-nav mr-auto mt-2 mt-lg-0" id="navigacija"
            style="font-family:'Jost', serif; font-size:17px; font-weight:400;">
            <li class="nav-item" id="login">
              <a class="nav-link" href="login.php">Početna <span class="sr-only">(current)</span></a>
            </li>
            <?php if (!isset($_SESSION['id_putnika'])) : ?>
              <script>
                dodajNav("nav-item", "signup", "nav-link", "signup.php", "Registruj se", document.getElementById("navigacija"));
              </script>
            <?php endif ?>
            <li class="nav-item" id="letovi">
              <a class="nav-link" href="letovi.php">Pretraga letova</a>
            </li>
            <li class="nav-item" id="destinacije">
              <a class="nav-link" id="aDest" href="destinacije.php">Destinacije</a>
            </li>
            <?php if (isset($_SESSION['id_putnika'])) : ?>
              <script>
                dodajNav("nav-item", "profil", "nav-link", "profil.php", "Profil", document.getElementById("navigacija"));
                dodajNav("nav-item", false, "nav-link", "baza/logout.php", "Izloguj se", document.getElementById("navigacija"));
              </script>
            <?php endif ?>
            <?php if (!isset($_SESSION['id_putnika'])) : ?>
              <script>
                dodajNav("nav-item", false, "nav-link disabled", "#", "Izloguj se", document.getElementById("navigacija"));
              </script>
            <?php endif ?>
            <?php if (isset($_SESSION['id_putnika']) && $_SESSION['ime'] == 'admin') : ?>
              <script>
                dodajNav("nav-item", "admin", "nav-link", "admin.php", "Admin", document.getElementById("navigacija"));
              </script>
            <?php endif ?>
          </ul>
          <?php if (!isset($_SESSION['id_putnika'])) : ?>
            <script>
              var form = document.createElement("form");
              form.setAttribute("class", "form-inline my-2 my-lg-0");
              form.setAttribute("action", "baza/logovanje.php");
              form.setAttribute("method", "post");
              form.setAttribute("id", "prijava_forma");
              inputPoc("form-control mr-sm-2", "username", "text", "Korisničko ime/Mejl", form);
              inputPoc("form-control mr-sm-2", "lozinka", "password", "Lozinka...", form);
              var dugme = document.createElement("button");
              dugme.setAttribute("class", "btn btn-outline-primary my-2 my-sm-0");
              dugme.setAttribute("name", "login-submit");
              dugme.setAttribute("type", "submit");
              dugme.innerHTML = "Prijavi se";
              form.appendChild(dugme);
              document.getElementById("navbarTogglerDemo02").appendChild(form);
            </script>
          <?php endif ?>
          <script>
            var pocetni = new Object();
            var id, imeGrada, koorX, koorY;
            var link = window.location.href;
            var gradovi;
            id = 2;

            function radi() {
              if (link.includes("destinacije")) {
                var btnDrop;

                console.log("cita bazu");
                $.get("baza/novoBiranjeDestinacije.php?citaj=true", function (data) {
                  gradovi = JSON.parse(data);
                  //console.log(gradovi[0].ime);
                  var dropdown = document.createElement("div");
                  dropdown.classList.add("dropdown");
                  dropdown.setAttribute("style", "padding-left:1%");
                  btnDrop = document.createElement("button");
                  btnDrop.id = "pocetak";
                  btnDrop.classList.add("btn");
                  btnDrop.classList.add("btn-secondary");
                  btnDrop.classList.add("dropdown-toggle");
                  btnDrop.setAttribute("type", "button");
                  btnDrop.setAttribute("data-toggle", "dropdown");
                  btnDrop.setAttribute("aria-haspopup", "true");
                  btnDrop.setAttribute("aria-expanded", "false");

                  if(localStorage.getItem("pocetak") === null) {
                    console.log("prazan storage");
                    btnDrop.innerHTML = gradovi[0].ime;
                    btnDrop.value = gradovi[0].id;
                    pocetni = gradovi[0];
                    localStorage.setItem("pocetak", JSON.stringify(pocetni));
                    console.log("sad je pun:");
                    console.log(JSON.parse(localStorage.getItem("pocetak")));
                  }
                  else {
                    console.log("pun storage");
                    var p = JSON.parse(localStorage.getItem("pocetak"));
                    var id = p.id;
                    var ime = p.ime;
                    var koorX = p.lokacija_x;
                    var koorY = p.lokacija_y;
                    btnDrop.value = id;
                    btnDrop.innerHTML = ime;
                    pocetni = {
                      id: id,
                      ime: ime,
                      lokacija_x: koorX,
                      lokacija_y: koorY
                    };
                  }
                  
                  dropdown.appendChild(btnDrop);
                  var meni = document.createElement("div");
                  meni.classList.add("dropdown-menu");
                  meni.setAttribute("aria-labelledby", "dropdownMenuButton");
                  for (var i = 0; i < gradovi.length; i++) {
                    var item = document.createElement("button");
                    item.classList.add("dropdown-item");
                    item.setAttribute("value", gradovi[i].ime);
                    item.setAttribute("id", gradovi[i].id);
                    var s = "myFunction('" + gradovi[i].id + "','" + gradovi[i].ime + "','" + gradovi[i]
                      .lokacija_x + "','" + gradovi[i].lokacija_y + "')"; //pazi na ovu liniju
                    item.setAttribute("onclick", s);
                    item.innerHTML = gradovi[i].ime;
                    meni.appendChild(item);
                  }
                  dropdown.appendChild(meni);
                  document.getElementById("navbarTogglerDemo02").appendChild(dropdown);

                  ajaxLoaded();
                });
                //myFunction(gradovi[0].id, gradovi[0].ime, gradovi[0].koorX, gradovi[0].koorY);
              }
              else localStorage.clear();
              if (link.includes("admin")) ucitavajAdmin();
            }

            var pathname = window.location.pathname;
            if (pathname.indexOf("destinacije") != -1) {
              document.getElementById("destinacije").classList.add('active');
              console.log("Ovo su destinacije");
              document.getElementById("aDest").href = pathname;
            }
            if (pathname.indexOf("login") != -1) {
              document.getElementById("login").classList.add('active');
              console.log("Ovo je login");
            }
            if (pathname.indexOf("signup") != -1) {
              document.getElementById("signup").classList.add('active');
              console.log("Ovo je registracija");
            }
            if (pathname.indexOf("letovi") != -1) {
              document.getElementById("letovi").classList.add('active');
              console.log("Ovo su letovi");
            }
            if (pathname.indexOf("admin") != -1) {
              document.getElementById("admin").classList.add('active');
              console.log("Ovo je admin");
            }
            if (pathname.indexOf("kupovina") != -1) {
              dodajNav("nav-item active", false, "nav-link", "#", "Kupovina", document.getElementById("navigacija"));
              console.log("Ovo je kupovina");
            }
          </script>

        </div>
      </nav>
    </header>



