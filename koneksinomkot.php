<?php

if (isset($_GET['gudang'])) {
  $gudang = $_GET['gudang'];
} else {
  $gudang = 'ALL';
}

if ($gudang == 'KRW') {
  $hostdb = "192.168.200.33";
  $userdb = "indoarsip";
  $passworddb = "P@ssw0rd";
  $db = 'dbarsip';
} elseif ($gudang == 'BDG') {
  $hostdb = "192.168.200.35";
  $userdb = "indoarsip";
  $passworddb = "P@ssw0rd";
  $db = 'dbarsip';
} elseif ($gudang == 'SMG') {
  $hostdb = "192.168.200.37";
  $userdb = "indoarsip";
  $passworddb = "P@ssw0rd";
  $db = 'dbarsip';
} elseif ($gudang == 'KLT') {
  $hostdb = "192.168.200.38";
  $userdb = "indoarsip";
  $passworddb = "P@ssw0rd";
  $db = 'dbarsip';
} elseif ($gudang == 'SBY') {
  $hostdb = "192.168.200.34";
  $userdb = "indoarsip";
  $passworddb = "P@ssw0rd";
  $db = 'dbarsip';
} elseif ($gudang == 'MKS') {
  $hostdb = "192.168.200.36";
  $userdb = "indoarsip";
  $passworddb = "P@ssw0rd";
  $db = 'dbarsip';
} elseif ($gudang == 'MDN') {
  $hostdb = "192.168.200.39";
  $userdb = "indoarsip";
  $passworddb = "P@ssw0rd";
  $db = 'dbarsip';
} else {
  $hostdb = "192.168.200.33";
  $userdb = "indoarsip";
  $passworddb = "P@ssw0rd";
  $db = 'dbarsip';
}

$koneksi_1 = mysqli_connect($hostdb, $userdb, $passworddb, 'dbarsip') or die("koneksi gagal " . mysqli_connect_error());
