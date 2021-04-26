<?php
session_start();
include_once '_conn.php'; ?>
<?php
$username	= $_POST['username'];
$password	= $_POST['password'];

$login	= mysql_query("SELECT * FROM user WHERE id_user='" . $username . "' AND password='" . $password . "' AND level IN ('admin','disposisi')");
$ketemu	= mysql_num_rows($login);

if ($ketemu > 0) {

	$row = mysql_fetch_array($login);

	$_SESSION['username']	= $username;
	$_SESSION['nama']		= $row['nama_lengkap'];
	$_SESSION['kodplg']		= $row['KODPLG'];
	$_SESSION['kodbag']		= $row['KODBAG'];
	$_SESSION['namplg']		= $row['NAMPLG'];
	$_SESSION['level']		= $row['level'];
	$_SESSION['id_user']	= $row['id_user'];
	$_SESSION['disposisi']	= $row['disposisi'];
	$_SESSION['email']		= $row['email'];
	$_SESSION['kodgud']		=  $row['kodgud'];;
	$_SESSION['kelamin']		= $row['kelamin'];


	//mysql_query("INSERT INTO log (id_user, username, tgl_log, keterangan) values('$r[id_user]','$username','$thn_jam_sekarang','Log-in To Halaman Utama')");

	//header('location:index.php?module=home');

}

?>

