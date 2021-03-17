<?php
date_default_timezone_set("Asia/Jakarta");

$server = "192.168.200.30";
$username = "root";
$password = "P@ssw0rd";
$database = "db_customer";

$con = mysqli_connect($server, $username, $password, $database); // or die("koneksi gagal");
