<?php

require_once __DIR__ . "/../security/Auth.php";

Auth::logout();
header('Location: /trabFinal/src/pages/home.php');
?>