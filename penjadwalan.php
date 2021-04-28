<?php
ini_set('date.timezone', 'Asia/Jakarta');
date_default_timezone_set('Asia/Jakarta');

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

?>
<script src="./plugins/daterangepicker.js"></script>

<?php
switch ($_GET['act']) {

    default:
        $max = 1;
        $smua = "SELECT MAX(RIGHT(idtransaksi, 5)) AS maxi FROM jadwal_h WHERE kodgud='$gudang'";

        $result = mysqlI_query($koneksi_1, $smua);
        while ($rowe = mysqlI_fetch_array($result)) {
            //$maxx = $rowe[maxi] + 1; 
            $max = substr($rowe['maxi'], 0) + 1;
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

        $id_transaksi = "$gudang-" . $max0;

        $sekarang1 = date("Ym");
        $tahun = substr($sekarang1, 0, 4);
        $bulan = substr($sekarang1, 4, 2);
        $tahun1 = substr($sekarang1, 2, 2);


        //echo "$tahun";
?>
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <form class="form-group" name='daftar' method=POST action='action.php?module=penjadwalan&act=tambah&gudang=<?php echo $gudang ?>' id='form1'>
                        <div class="form-group">



                            <label>ID TRANSAKSI</label>
                            <input type=' text' class="form-control" name='id_transaksi' value='<?= $id_transaksi ?>' readonly>


                            <label>MOBIL</label>

                            <select class="form-control" name='mobil'>
                                <?php
                                $driver = "SELECT * FROM datkot_h_mobil ORDER BY NO_MOBIL ASC";
                                $drv = mysqli_query($koneksi_1, $driver);

                                while ($row = mysqli_fetch_array($drv)) {
                                    echo "<option value='$row[NO_MOBIL]'>$row[NO_MOBIL]</option>'";
                                }


                                ?></select>

                            <td>DRIVER</td>
                            <td>
                                <select class="form-control" name='nikdriver'>
                                    <?php
                                    $driver = "SELECT * FROM login ORDER BY nama_lengkap ASC";
                                    $drv = mysqli_query($koneksi_1, $driver);

                                    while ($row = mysqli_fetch_array($drv)) {
                                        echo "<option value='$row[nik]'>$row[nik] - ($row[nama_lengkap])</option>'";
                                    }
                                    ?>
                                </select>

                                <label>EKSPEDISI</label>

                                <select class="form-control" name='nikekspedisi'>

                                    <?php
                                    $ekspedisi = "SELECT * FROM login ORDER BY nama_lengkap ASC";
                                    $eks = mysqli_query($koneksi_1, $ekspedisi);
                                    echo "<option value=''>-- Pilih EKSPEDISI --</option>'";
                                    while ($row = mysqli_fetch_array($eks)) {
                                        echo "<option value='$row[nik]'>$row[nik] - ($row[nama_lengkap])</option>'";
                                    }
                                    ?>
                                </select>

                                <label>Tanggal</label>

                                <input class="form-control" type='text' name='tanggal' value='' id='tgla'>


                                <div colspan=4 align=center><input type='submit' class='btn btn-info' value='Simpan' />

                                </div>
                    </form>
                </div>
            </div>
        </div>
    <?php

        break;

    case "tambah2":

        $query = mysqli_query($koneksi_1, "SELECT * FROM jadwal_h WHERE idtransaksi='$_GET[id]' AND kodgud='$gudang'");
        $jdwl = mysqli_fetch_array($query);

        $query2 = mysqli_query($koneksi_1, "SELECT nama_lengkap FROM login WHERE nik='$jdwl[nikdriver]'");
        $drv = mysqli_fetch_array($query2);
        $query3 = mysqli_query($koneksi_1, "SELECT nama_lengkap FROM login WHERE nik='$jdwl[nikekspedisi]'");
        $eks = mysqli_fetch_array($query3);

        $sekarang1 = date("Ym");
        $tahun = substr($sekarang1, 0, 4);
        $bulan = substr($sekarang1, 4, 2);
        $tahun1 = substr($sekarang1, 2, 2);
    ?>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">

                            Silahkan Lengkapi Data Berikut
                        </div>
                        <div class="card-body">


                            <table width='100%'>
                                <input type='hidden' name='id_user' value='<?= $_SESSION[namauser] ?>' readonly />
                                <tr>
                                    <td width='0.2%'>ID TRANSAKSI</td>

                                    <td width='5%'><input type='text' class="form-control" name='id_transaksi' value='<?= $jdwl['idtransaksi'] ?>' size='25' readonly />
                                        <font color=red size=2px></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td>NO MOBIL</td>

                                    <td><input type='text' class="form-control" name='id_transaksi' value='<?= $jdwl[no_mobil] ?>' size='25' readonly /></td>
                                </tr>
                                <tr>
                                    <td>DRIVER</td>

                                    <td width='5%'><input type='text' class="form-control" name='id_transaksi' value='<?= $drv[nama_lengkap] ?>' size='25' readonly /></td>
                                </tr>
                                <tr>
                                    <td>EKSPEDISI</td>
                                    <td width='5%'><input type='text' class="form-control" name='id_transaksi' value='<?= $eks[nama_lengkap] ?>' size='25' readonly /></td>
                                </tr>
                                <tr>
                                    <td>TANGGAL</td>
                                    <td width='5%'><input type='text' class="form-control mb-3" name='id_transaksi' value='<?= $jdwl[tanggal] ?>' size='25' readonly /></td>
                                </tr>
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
                                </form>
                            </table>

                            <?php
                            $id    = $_GET['id'];

                            if ($_GET['jns'] == '1') {
                                $result = mysqli_query($koneksi_1, "SELECT * FROM tboxkosong WHERE NO_ORDER!='' ORDER BY TGLINPUT DESC");
                            } elseif ($_GET['jns'] == '2') {
                                $result = mysqli_query($koneksi_1, "SELECT * FROM ppba WHERE NO_ORDER!='' ORDER BY TGLINPUT DESC");
                            } elseif ($_GET['jns'] == '3') {
                                $result = mysqli_query($koneksi_1, "SELECT * FROM tandaterima WHERE NO_ORDER!='' AND STATUS='1' ORDER BY TGLINPUT DESC");
                            } elseif ($_GET['jns'] == '4') {
                                $result = mysqli_query($koneksi_1, "SELECT * FROM sjttmusnah WHERE NO_ORDER!='' ORDER BY TGLINPUT DESC");
                            } elseif ($_GET['jns'] == '5') {
                                $result = mysqli_query($koneksi_1, "SELECT * FROM sjttkeluar WHERE NO_ORDER!='' ORDER BY TGLINPUT DESC");
                            } elseif ($_GET['jns'] == '6') {
                                $result = mysqli_query($koneksi_1, "SELECT * FROM sjttself WHERE NO_ORDER!='' ORDER BY TGLINPUT DESC");
                            }
                            $jsArray = "var NO_PPBA1 = new Array();\n";

                            $query = mysqli_query($koneksi_1, "select * from jadwal_d where idtransaksi='$id' order by urut asc");

                            ?>

                            <table style='width:100%;' border='1px'>
                                <thead>
                                    <tr>

                                        <td bgcolor="#CCCCCC">SERVICE</td>
                                        <td bgcolor="#CCCCCC"><strong>NO SJTT</strong></td>
                                        <td bgcolor="#CCCCCC"><strong>NO ORDER</strong></td>
                                        <td bgcolor="#CCCCCC"><strong>KODPLG</strong></td>
                                        <td bgcolor="#CCCCCC"><strong>TANGGAL</strong></td>
                                        <td bgcolor="#CCCCCC"><strong>LAYANAN</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($data = mysqli_fetch_array($query)) {
                                        $count = mysqli_query($koneksi_1, "select COUNT(idtransaksi) AS urut from jadwal_d where idtransaksi='$_GET[id]'");
                                        $dt = mysqli_fetch_array($count);
                                        $urt = $dt['urut'] + 1;
                                    ?>
                                        <tr>


                                            <td><?php if ($data['jns'] == '1') {
                                                    echo "Box Kosong";
                                                } elseif ($data['jns'] == '2') {
                                                    echo "Peminjaman";
                                                } elseif ($data['jns'] == '3') {
                                                    echo "Penerimaan";
                                                } elseif ($data['jns'] == '4') {
                                                    echo "Pemusnahan";
                                                } elseif ($data['jns'] == '5') {
                                                    echo "Keluar Pelanggan";
                                                } elseif ($data['jns'] == '6') {
                                                    echo "KP-SELF";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $data['no_sjtt']; ?></td>
                                            <td><?php echo $data['no_order']; ?></td>
                                            <td><?php echo $data['KODPLG']; ?></td>
                                            <td><?php echo $data['tgl_kirim']; ?></td>
                                            <td><?php echo $data['layanan']; ?></td>
                                            <td>&nbsp;</td>
                                        </tr>

                                    <?php } ?>
                                    <tr>
                                        <form name='daftar2' method=POST action='./action.php?module=penjadwalan&act=tambah2&gudang=<?php echo $gudang ?>' id='form1'>
                                            <input type="hidden" name="urut" value="<?php echo $urt; ?>" required="required" title='*Harus Diisi' style='width:100%;white-space: nowrap;' />
                                            <td>
                                                <select name="jns" onChange="location = '<?php $_SERVER['PHP_SELF'] ?>?page=jadwal&gudang=<?php echo $gudang ?>&act=tambah2&id=<?php echo $_GET['id']; ?>&jns=' + this.value " data-live-search="true" data-style="btn-default">
                                                    <option value="">- SILAHKAN PILIH -</option>
                                                    <option value="1" <?php if ($_GET['jns'] == '1') {
                                                                            echo "selected";
                                                                        } ?>> -- Box Kosong --</option>
                                                    <option value="2" <?php if ($_GET['jns'] == '2') {
                                                                            echo "selected";
                                                                        } ?>> -- Peminjaman --</option>
                                                    <option value="3" <?php if ($_GET['jns'] == '3') {
                                                                            echo "selected";
                                                                        } ?>> -- Penerimaan --</option>
                                                    <option value="4" <?php if ($_GET['jns'] == '4') {
                                                                            echo "selected";
                                                                        } ?>> -- Pemusnahan --</option>
                                                    <option value="5" <?php if ($_GET['jns'] == '5') {
                                                                            echo "selected";
                                                                        } ?>> -- Keluar Pelanggan --</option>
                                                    <option value="6" <?php if ($_GET['jns'] == '6') {
                                                                            echo "selected";
                                                                        } ?>> -- KP-SELF --</option>
                                                </select>
                                            </td>
                                            <td>
                                                <?php if ($_GET['jns'] == '1') { ?>
                                                    <select name="no_sjtt" onchange="changeValue(this.value)">
                                                        <option>- SILAHKAN PILIH -</option>
                                                        <?php while ($row = mysqli_fetch_array($result)) { ?>
                                                            <option value="<?php echo $row['NO_TBOX']; ?>"><?php echo $row['NO_TBOX']; ?> / <?php echo $row['KODPLG']; ?></option>
                                                        <?php $jsArray .= "NO_PPBA1['" . $row['NO_TBOX'] . "'] = {name:'" . addslashes($row['KODPLG']) . "',name1:'" . addslashes($row['TGLKIRIM']) . "',desc:'" . addslashes($row['NO_ORDER']) . "',odr:'" . addslashes($row['SERVICE']) . "'};\n";
                                                        }

                                                        echo $jsArray; ?>
                                                    </select>
                                                <?php } elseif ($_GET['jns'] == '2') { ?>
                                                    <select name="no_sjtt" onchange="changeValue(this.value)">
                                                        <option>- SILAHKAN PILIH -</option>
                                                        <?php while ($row = mysqli_fetch_array($result)) { ?>
                                                            <option value="<?php echo $row['NO_PPBA'];  ?>"><?php echo $row['NO_PPBA']; ?> / <?php echo $row['KODPLG']; ?></option>
                                                        <?php $jsArray .= "NO_PPBA1['" . $row['NO_PPBA'] . "'] = {name:'" . addslashes($row['KODPLG']) . "',name1:'" . addslashes($row['TGLKIRIM']) . "',desc:'" . addslashes($row['NO_ORDER']) . "',fax:'" . addslashes($row['TGLINPUT']) . "',odr:'" . addslashes($row['SERVICE']) . "'};\n";
                                                        } ?>
                                                    </select>
                                                <?php } elseif ($_GET['jns'] == '3') { ?>
                                                    <select name="no_sjtt" onchange="changeValue(this.value)">
                                                        <option>- SILAHKAN PILIH -</option>
                                                        <?php while ($row = mysqli_fetch_array($result)) { ?>
                                                            <option value="<?php echo $row['NO_SJTT']; ?>"><?php echo $row['NO_SJTT']; ?> / <?php echo $row['KODPLG']; ?></option>
                                                        <?php $jsArray .= "NO_PPBA1['" . $row['NO_SJTT'] . "'] = {name:'" . addslashes($row['KODPLG']) . "',name1:'" . addslashes($row['TGLINPUT']) . "',desc:'" . addslashes($row['NO_ORDER']) . "',odr:'" . addslashes('Penerimaan') . "'};\n";
                                                        } ?>
                                                    </select>
                                                <?php } elseif ($_GET['jns'] == '4') { ?>
                                                    <select name="no_sjtt" onchange="changeValue(this.value)">
                                                        <option>- SILAHKAN PILIH -</option>
                                                        <?php while ($row = mysqli_fetch_array($result)) { ?>
                                                            <option value="<?php echo $row['NO_SJTTMUSNAH']; ?>"><?php echo $row['NO_SJTTMUSNAH']; ?></option>
                                                        <?php $jsArray .= "NO_PPBA1['" . $row['NO_SJTTMUSNAH'] . "'] = {name:'" . addslashes($row['KODPLG']) . "',name1:'" . addslashes($row['TGLINPUT']) . "',desc:'" . addslashes($row['NO_ORDER']) . "',odr:'" . addslashes($row['SERVICE']) . "'};\n";
                                                        } ?>
                                                    </select>
                                                <?php } elseif ($_GET['jns'] == '5') { ?>
                                                    <select name="no_sjtt" onchange="changeValue(this.value)">
                                                        <option>- SILAHKAN PILIH -</option>
                                                        <?php while ($row = mysqli_fetch_array($result)) { ?>
                                                            <option value="<?php echo $row['NO_SJTTKELUAR']; ?>"><?php echo $row['NO_SJTTKELUAR']; ?></option>
                                                        <?php $jsArray .= "NO_PPBA1['" . $row['NO_SJTTKELUAR'] . "'] = {name:'" . addslashes($row['KODPLG']) . "',name1:'" . addslashes($row['TGLINPUT']) . "',desc:'" . addslashes($row['NO_ORDER']) . "',odr:'" . addslashes($row['SERVICE']) . "'};\n";
                                                        } ?>
                                                    </select>
                                                <?php } elseif ($_GET['jns'] == '6') { ?>
                                                    <select name="no_sjtt" onchange="changeValue(this.value)">
                                                        <option>- SILAHKAN PILIH -</option>
                                                        <?php while ($row = mysqli_fetch_array($result)) { ?>
                                                            <option value="<?php echo $row['NO_SJTTSELF']; ?>"><?php echo $row['NO_SJTTSELF']; ?></option>
                                                        <?php $jsArray .= "NO_PPBA1['" . $row['NO_SJTTSELF'] . "'] = {name:'" . addslashes($row['KODPLG']) . "',name1:'" . addslashes($row['TGLINPUT']) . "',desc:'" . addslashes($row['NO_ORDER']) . "',odr:'" . addslashes($row['SERVICE']) . "'};\n";
                                                        } ?>
                                                    </select>
                                                <?php } ?>
                                            </td>
                                            <td><input type="text" name="no_order" id="NO_ORDER" title='*Harus Diisi' required=required>
                                                <input type="hidden" name="id_transaksi" value="<?php echo $_GET['id']; ?>" title='*Harus Diisi' required=required>
                                            </td>
                                            <td style=' white-space: nowrap;'><input type="text" name="kodplg" id="KODPLG" required=required title='*Harus Diisi' /></td>
                                            <td><input type="text" name="tgl_kirim" id="TGLKIRIM" title='*Harus Diisi' required=required SIZE=12></td>
                                            <td><input type="text" name="service" id="SERVICE" title='*Harus Diisi' required=required> </td>
                                </tbody>
                                <input type='submit' class='btn btn-info mb-3' value='Simpan' />
                                </form>



                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            <?php echo $jsArray; ?>

            function changeValue(id) {
                document.getElementById('KODPLG').value = NO_PPBA1[id].name;
                document.getElementById('TGLKIRIM').value = NO_PPBA1[id].name1;
                document.getElementById('NO_ORDER').value = NO_PPBA1[id].desc;
                document.getElementById('SERVICE').value = NO_PPBA1[id].odr;
            };
        </script>
        </table>
        <p>&nbsp; </p>


<?php

        break;
}
?>