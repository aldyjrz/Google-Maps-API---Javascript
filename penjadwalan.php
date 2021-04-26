<?php
ini_set('date.timezone', 'Asia/Jakarta');
date_default_timezone_set('Asia/Jakarta');
?>
<script src="./js/jquery.js"></script>
<script src="./js/development-bundle/ui/jquery.ui.core.js"></script>
<script src="./js/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="./js/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link href="./js/themes/sunny/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css" />

<script language="JavaScript">
    function calc_laporan() {
        document.tambah.total.value =
            parseInt(document.tambah.pokok.value) +
            parseInt(document.tambah.basil.value) +
            parseInt(document.tambah.infaq.value) +
            parseInt(document.tambah.simpanan.value);
    }

    function calc_laporan1() {
        document.tambah.basil_anggota.value =
            100 -
            parseInt(document.tambah.basil_bmt.value);
    }
</script>

<style type="text/css">
    #form1 label.error {
        color: red;
        padding: 5px 5px 5px 0.1em;
        font-size: 8pt;
        margin-left: 2px;
        font-weight: bold;
        background-color: #FBFCC2;
    }

    th {
        padding: 2px;
    }

    #form1 label.valid {
        color: green;
        padding: 5px 5px 5px 0.1em;
        font-size: 8pt;
        margin-left: 5px;
        font-weight: bold;
        background-color: #FBFCC2;
    }

    th {
        padding: 2px;
    }
</style>

<script language="javascript">
    function validate(field) {
        var valid = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
        var ok = "yes";
        var temp;
        for (var i = 0; i < field.value.length; i++) {
            temp = "" + field.value.substring(i, i + 1);
            if (valid.indexOf(temp) == "-1") ok = "no";
        }
        if (ok == "no") {
            alert("Maaf Tidak Bisa Pakai Dengan Angka !!");
            valid.indexOf(temp) == "";
        }
        return (true);
    }
</script>
<script language="javascript">
    function validates(field) {
        var valid = "1234567890"
        var ok = "yes";
        var temp;
        for (var i = 0; i < field.value.length; i++) {
            temp = "" + field.value.substring(i, i + 1);
            if (valid.indexOf(temp) == "-1") ok = "no";
        }
        if (ok == "no") {
            alert("Maaf Tidak Bisa Pakai Dengan Huruf !!");
            valid.indexOf(temp) == "";
        }
        return (true);
    }
</script>
<?php
//include "function_masterkejadian.php";

$max = 1;
$smua = "SELECT MAX(RIGHT(idtransaksi, 6)) AS maxi FROM jadwal_h WHERE kodgud='KRW'";

$result = mysql_query($smua);
while ($rowe = mysql_fetch_array($result)) {
    //$maxx = $rowe[maxi] + 1; 
    $max = substr($rowe[maxi], 0) + 1;
}
if (strlen($max) == 1)
    $max0 = "0000" . $max;
elseif (strlen($max) == 2)
    $max0 = "000" . $max;
elseif (strlen($max) == 3)
    $max0 = "00" . $max;
elseif (strlen($max) == 4)
    $max0 = "00" . $max;
elseif (strlen($max) == 5)
    $max0 = $max;

$id_transaksi = "KRW-" . $max0;
//$tampil=mysql_query("SELECT MAX(NO_SJTT) AS jml FROM tandaterima");
//$c=mysql_fetch_array($tampil); 
//$no_sjtt="STT-".date("YmdHis");
$sekarang1 = date("Ym");
$tahun = substr($sekarang1, 0, 4);
$bulan = substr($sekarang1, 4, 2);
$tahun1 = substr($sekarang1, 2, 2);


//echo "$tahun";

echo "<form name='daftar' method=POST action='./aksi.php?module=penjadwalan&act=tambah' id='form1'";
echo "<fieldset><legend><h1>Silahkan Lengkapi Data Berikut</h1></legend>";
echo " <table class='form-div' width='100%' >
		<input type='hidden' name='id_user' value='$_SESSION[namauser]' readonly />
<tr><td width='15%'>ID TRANSAKSI</td><td width='2%'> : </td><td><input type='text' name='id_transaksi' value='$id_transaksi' size='25' readonly /> <font color=red size=2px></font></td></tr>";


echo "<tr><td>MOBIL</td><td> : </td><td>
<select name='mobil'>";

$driver = "SELECT * FROM datkot_h_mobil ORDER BY NO_MOBIL ASC";
$drv = mysql_query($driver);

while ($row = mysql_fetch_array($drv)) {
    echo "<option value='$row[NO_MOBIL]'>$row[NO_MOBIL]</option>'";
}
echo "</select>
</td></tr>

<tr><td>DRIVER</td><td> : </td><td>
<select name='nikdriver'>";

$driver = "SELECT * FROM login ORDER BY nama_lengkap ASC";
$drv = mysql_query($driver);

while ($row = mysql_fetch_array($drv)) {
    echo "<option value='$row[nik]'>$row[nik] - ($row[nama_lengkap])</option>'";
}
echo "</select>
</td></tr>

<tr><td>EKSPEDISI</td><td> : </td><td>
<select name='nikekspedisi'>";

$ekspedisi = "SELECT * FROM login ORDER BY nama_lengkap ASC";
$eks = mysql_query($ekspedisi);
echo "<option value=''>-- Pilih EKSPEDISI --</option>'";
while ($row = mysql_fetch_array($eks)) {
    echo "<option value='$row[nik]'>$row[nik] - ($row[nama_lengkap])</option>'";
}
echo "</select>
</td></tr>
"; ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#tgla").datepicker({
            dateFormat: "yy-mm-dd",
            defaultDate: "+0w",
            changeYear: true,
            changeMontd: true
        });

    });
</script>
<?php

echo "<tr><td>Tanggal</td><td> : </td><td><input type=text name='tanggal' value='' id='tgla'> 

<tr><td colspan=4 align=center><input type='submit' value='Simpan'/> <input type=button value=Kembali onclick=self.history.back()> </form>";

if ($_GET['id'] != '') {
    include "jadwal.php";
}

echo "</table>";
?>