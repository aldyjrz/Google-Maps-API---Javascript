<?php

$server = "localhost";
$username = "root";
$password = "P@ssw0rd";
$database = "db_customer";

$kon = mysqli_connect($server, $username, $password, $database) or die("Koneksi gagal - " . mysqli_connect_error());
