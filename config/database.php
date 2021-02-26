<?php
date_default_timezone_set("Asia/Jakarta");

$server = "localhost";
$username = "root";
$password = "";
$database = "db_customerA";
$base_url = "http://115.124.73.22:8091/android/api/customer/";

$con = mysqli_connect($server, $username, $password, $database); // or die("koneksi gagal");

 
?>