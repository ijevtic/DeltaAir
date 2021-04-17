<?php

session_start();
session_unset();  //brise sve
session_destroy();
header("Location: ../login.php");
