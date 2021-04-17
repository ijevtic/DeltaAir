<?php
require "header.php";
?>

<div id="same" class="alert alert-warning" role="alert" style="display: none;">
    Korisnik sa istim korisničkim imenom/mejlom već postoji!
</div>
<div id="success" class="alert alert-success" role="alert" style="display: none;">
    Registracija uspešna!
</div>
<div id="error" class="alert alert-danger" role="alert" style="display: none;">
    Došlo je do greške!
</div>
<main class="my-form">
    <div class="cotainer">
        <div class="row justify-content-center mr-0 mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Registracija</div>
                    <div class="card-body">
                        <form name="my-form" class="needs-validation" action="baza/registracija.php" method="post"
                            novalidate>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="input_ime" name="ime" placeholder="Ime"
                                        required>
                                    <div class="invalid-feedback" id="wrong_ime">
                                        Ime lošeg formata!
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="input_prezime" name="prezime"
                                        placeholder="Prezime" required>
                                    <div class="invalid-feedback" id="wrong_prezime">
                                        Prezime lošeg formata!
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="input_username" name="username"
                                        placeholder="Korisničko ime" required>
                                    <div class="invalid-feedback" id="wrong_username">
                                        Korisničko ime lošeg formata!
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="input_jmbg" name="jmbg"
                                        placeholder="JMBG" required>
                                    <div class="invalid-feedback" id="wrong_jmbg">
                                        JMBG lošeg formata!
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-3">
                                    <input type="password" class="form-control" id="input_lozinka" name="lozinka"
                                        placeholder="Lozinka..." required>
                                    <div class="invalid-feedback" id="wrong_lozinka">
                                        Unesi lozinku!
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="password" class="form-control" id="input_lozinka_copy"
                                        name="lozinka-copy" placeholder="Potvrdi lozinku..." required>
                                    <div class="invalid-feedback" id="wrong_lozinka_copy">
                                        Ponovna lozinka netačna!
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="input_mejl" name="mejl"
                                        placeholder="Mejl" required>
                                    <div class="invalid-feedback" id="wrong_mejl">
                                        Mejl pogrešnog formata!
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="input_telefon" name="telefon"
                                        placeholder="Telefon" required>
                                    <div class="invalid-feedback" id="wrong_telefon">
                                        Telefon pogrešnog formata!
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-auto">
                                    <button type="submit" id="submit" name="signup-submit" class="btn btn-primary">
                                        Registruj se!
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    var url = window.location.href;
    var niz = url.split("=");
    if (niz[1] == 'success')
        document.getElementById("success").style.display = "block";
    if (niz[1] == 'exists') {
        document.getElementById("same").style.display = "block";
    }
    if (niz[1] == 'sqlerror')
        document.getElementById("error").style.display = "block";


    function isValid(val) {
        var regex = /^[a-zA-Z0-9ČčĆćŽžŠšĐđČ]*$/;
        return regex.test(val);
    }

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function check(id, wrong) {
        var el = document.getElementById(id);
        if (el.value == "" || !isValid(el.value)) {
            console.log(id);
            el.value = "";
            return false;
        }
        return true;
    }

    function provera() {
        var ver = true;

        if (!check("input_ime", "wrong_ime")) ver = false;
        if (!check("input_prezime", "wrong_prezime")) ver = false;
        if (!check("input_username", "wrong_username")) ver = false;

        var pass = document.getElementById("input_lozinka");
        if (pass.value == "") {
            console.log("losa pass");
            ver = false;
            pass.value = "";
        }
        var pass_c = document.getElementById("input_lozinka_copy");
        if (pass_c.value == "" || pass_c.value != pass.value) {
            console.log("losa pass_c");
            ver = false;
            pass_c.value = "";
            pass.value = "";
        }
        var jmbg = document.getElementById("input_jmbg");
        if (jmbg.value == "" || isNaN(jmbg.value) || jmbg.value.length != 13) {
            console.log("los jmbg");
            ver = false;
            jmbg.value = "";
        }
        var mejl = document.getElementById("input_mejl");
        if (mejl.value == "" || !isEmail(mejl.value)) {
            console.log("los mejl");
            ver = false;
            mejl.value = "";
        }
        var fon = document.getElementById("input_telefon");
        if (fon.value == "" || isNaN(fon.value)) {
            console.log("los fon");
            ver = false;
            fon.value = "";
        }
        return ver;
    }

    (function () {
        'use strict';
        window.addEventListener('load', function () {

            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function (form) {

                form.addEventListener('submit', function (event) {
                    document.getElementById("error").style.display = "none";
                    document.getElementById("same").style.display = "none";
                    document.getElementById("success").style.display = "none";
                    if (!provera() || form.checkValidity() === false) {
                        console.log("LOSE");
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

<?php
require "footer.php";
?>