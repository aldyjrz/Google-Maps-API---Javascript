<?php
include 'config/database.php';


if (isset($_GET['gudang'])) {
  $gudang = $_GET['gudang'];
} else {
  $gudang = 'ALL';
}

if (isset($_GET['no_order'])) {
  $no_order = $_GET['no_order'];
} else {
  $no_order = 'ALL';
}
include "koneksinomkot.php";
?>
<!DOCTYPE html>
<html>
<?php
include "template/head.php";


?>

<?php
?>
<!-- Start Sidebar -->
<nav class="navbar navbar-expand-lg fixed-top  navbar-dark bg-dark">

  <a class="navbar-brand" href="#">Fleet Monitor</a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">

    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>


    </ul>

    <form class="form-inline my-2 my-lg-0">
      <span style='color:white;' class='mr-3'>Administrator</span>
      <a href="#" class="btn btn-outline-danger">Logout</a>

    </form>

  </div>

</nav>


<body>
  <main class="container ">
    <div class="row">
      <div class="col-md-12">
        <br>
        <div class="card">

          <form action="/fleet-monitor/index.php" method="GET">
            <div class="card">

              <label class="col-md-6 col-form-label">KODE GUDANG*</label>
              <div class="col-md-6">
                <select class="form-control" name='gudang' id="gudang" ?>'>
                  <option value="all">SHOW ALL</option>
                  <option value="KRW">KARAWANG</option>
                  <option value="SBY">SURABAYA</option>
                  <option value="BDG">BANDUNG</option>
                  <option value="SMG">SEMARANG</option>
                  <option value="MKS">MAKASSAR</option>
                  <option value="KLT">KLATEN</option>
                  <option value="MDN">MEDAN</option>


                </select>

                <button class="btn btn-info mb-3 mt-3">CARI LOKASI</button>
              </div>
              <label class="col-md-6 col-form-label">Berdasarkan No Order</label>
              <div class="col-md-6">
                <select class="form-control" name='no_order' id="no_order" value="<?php echo $_GET['no_order'] ?>">
                  <option value="all">SHOW ALL</option>

                  <?php

                  // if ($gudang != 'all' && $no_order != 'all') {
                  //   $marker = mysqli_query($con, "select * from wmt_order_h where read_status != 'selesai' and kodgud='$gudang' and  no_order='$no_order' and recmod > '2021-01-01';") or die(mysqli_error($con));
                  // } else if ($gudang != 'all' && $no_order == 'all') {
                  //   $marker = mysqli_query($con, "select * from wmt_order_h where read_status != 'selesai' and kodgud='$gudang' and recmod > '2021-01-01';") or die(mysqli_error($con));
                  // } else if ($gudang == 'all' && $no_order != 'all') {
                  //   $marker = mysqli_query($con, "select * from wmt_order_h where read_status != 'selesai' and no_order = '$no_order' and recmod > '2021-01-01';") or die(mysqli_error($con));
                  // } else {
                  //   $marker = mysqli_query($con, "select * from wmt_order_h where read_status != 'selesai' and  recmod > '2021-01-01';") or die(mysqli_error($con));
                  // }
                  if ($gudang == 'all') {
                    $marker =  mysqli_query($koneksi_1, "SELECT
   
                    `h`.`kodgud`      AS `kodgud`,
                    `d`.`no_order`    AS `no_order`,
                    `d`.`KODPLG`      AS `kodplg`,
                    `d`.`layanan`     AS `layanan`,
                    `h`.`idtransaksi` AS `idtransaksi`,
                    `h`.`no_mobil`    AS `no_mobil`,
                    `h`.`nikdriver`   AS `nikdriver`,
                    `h`.`recmod`      AS `recmod`,
                     `d`.`selesai`      AS `selesai`
                  FROM  `jadwal_d` `d`
                     JOIN `jadwal_h` `h`
                       ON  `d`.`idtransaksi` = `h`.`idtransaksi` 
                       WHERE h.recmod >  DATE(CURRENT_DATE() )AND d.selesai  != 'ya'  GROUP BY h.idtransaksi ");
                  } else if ($gudang != 'all') {
                    $marker =  mysqli_query($koneksi_1, "SELECT
   
                    `h`.`kodgud`      AS `kodgud`,
                    `d`.`no_order`    AS `no_order`,
                    `d`.`KODPLG`      AS `kodplg`,
                    `d`.`layanan`     AS `layanan`,
                    `h`.`idtransaksi` AS `idtransaksi`,
                    `h`.`no_mobil`    AS `no_mobil`,
                    `h`.`nikdriver`   AS `nikdriver`,
                    `h`.`recmod`      AS `recmod`,
                     `d`.`selesai`      AS `selesai`
                  FROM  `jadwal_d` `d`
                     JOIN `jadwal_h` `h`
                       ON  `d`.`idtransaksi` = `h`.`idtransaksi` 
                       WHERE h.recmod  > DATE(CURRENT_DATE()) AND d.selesai != 'ya' GROUP BY h.idtransaksi ");
                  }
                  if (mysqli_num_rows($marker) < 1) {
                    echo "<script>alert('tidak ada order aktif');</script>";
                  }

                  while ($data = mysqli_fetch_assoc($marker)) {


                    $order = $data['no_order'];
                    $idtrans = $data['idtransaksi'];
                    $plat = $data['no_mobil'];

                  ?>

                    <option value="<?php echo $order ?>"> <?php echo "$order - $idtrans - $plat"; ?></option>

                  <?php } ?>

                </select>

                <button class="btn btn-info mb-3 mt-3">CARI LOKASI</button>
              </div>

            </div>
        </div>
        </form>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <div id="map"></div>
      </div>

    </div>
    </div>
    </div>
  </main>

</body>
<link rel="stylesheet" href="style.css">
<script src="node_modules/jquery/dist/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA9cC9fl_RsrvMZj5v_Sa4W5iSj8C7QZ2Y&callback=initMap"> </script>

<script>
  var marker;

  var map;

  //inisialisasi awal google maps
  function initMap() {
    // set geolocation dan lokasi terakhir
    var x = navigator.geolocation;
    x.getCurrentPosition(success, failure);

    //  get current position jika success
    function success(position) {


      //belum di inisialisi untuk query lokasi terakhir
      var lastLocation = new google.maps.LatLng(-6.22574, 106.83);


      map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: lastLocation

      });



      updateMarker();
    }
    //kondisi jika gagal mendapatkan lokasi terkini
    function failure() {
      console.log("failed get coords");

    }



  }


  /////////////////LOOPING MARKER DRIVER///////////////////
  async function updateMarker() {


    <?php
    if (!isset($_GET['gudang'])) {
      $gudang = 'all';
    }
    if (!isset($_GET['no_order'])) {
      $no_order = 'all';
    }
    ?>
    $('#gudang').val('<?php echo $gudang ?>').prop('selected', true);
    $('#no_order').val('<?php echo $no_order ?>').prop('selected', true);



    <?php

    if ($no_order == 'all') {
      $marker =  mysqli_query($koneksi_1, "SELECT
 
                  `h`.`kodgud`      AS `kodgud`,
                  `d`.`no_order`    AS `no_order`,
                  `d`.`KODPLG`      AS `kodplg`,
                  `d`.`layanan`     AS `layanan`,
                  `h`.`idtransaksi` AS `idtransaksi`,
                  `h`.`no_mobil`    AS `no_mobil`,
                  `h`.`nikdriver`   AS `nikdriver`,
                  `h`.`recmod`      AS `recmod`,
                   `d`.`selesai`      AS `selesai`
                FROM  `jadwal_d` `d`
                   JOIN `jadwal_h` `h`
                     ON  `d`.`idtransaksi` = `h`.`idtransaksi` 
                     WHERE h.recmod  > DATE(CURRENT_DATE()) and  d.selesai != 'ya' GROUP BY h.idtransaksi ");
    } else  if ($gudang != "all" && $no_order != 'all') {
      $q = "SELECT  `h`.`kodgud`      AS `kodgud`,
      `d`.`no_order`    AS `no_order`,
      `d`.`KODPLG`      AS `kodplg`,
      `d`.`layanan`     AS `layanan`,
      `h`.`idtransaksi` AS `idtransaksi`,
      `h`.`no_mobil`    AS `no_mobil`,
      `h`.`nikdriver`   AS `nikdriver`,
      `h`.`recmod`      AS `recmod`,
       `d`.`selesai`      AS `selesai`
    FROM  `jadwal_d` `d`
       JOIN `jadwal_h` `h`
         ON  `d`.`idtransaksi` = `h`.`idtransaksi` 
         WHERE d.no_order = '$no_order' AND h.recmod  > DATE(CURRENT_DATE()) and d.selesai != 'ya' GROUP BY h.idtransaksi ";

      $marker =  mysqli_query($koneksi_1, $q);
    }

    while ($data = mysqli_fetch_assoc($marker)) {

      $order =  $data['no_order'];
      $nik = $data['nikdriver'];
      $q =  "SELECT * from tbl_drivergps where no_order = '$order'";

      $e = mysqli_query($con, $q);
      $d = mysqli_fetch_array($e);
      $latitude = $d['lati'];
      $longitude = $d['longi'];
      $no_order = $d['no_order'];
      $qq =  "SELECT * FROM `login`  WHERE `nik` =   '$nik'";

      $gg = mysqli_query($koneksi_1, $qq);
      $ee = mysqli_fetch_array($gg);
      $nama = $ee['nama_lengkap'];
      $plat = $data['no_mobil'];
      if ($latitude != '' || $longitude != '') {
    ?>

        addMarker(<?php echo $latitude ?>, <?php echo $longitude ?>, '<?php echo $no_order  ?>\n<?php echo  $nama ?>\n<?php echo  $plat ?>');


    <?php
      }
    }


    ?>


    function addMarker(lat, lng, info) {

      var coords = new google.maps.LatLng(lat, lng);

      //init marker sesuai looping posisi kordinat
      marker = new google.maps.Marker({
        position: coords,
        title: info,
        icon: {
          path: google.maps.SymbolPath.CIRCLE,
          scale: 10,
          fillOpacity: 1,
          strokeWeight: 2,
          fillColor: '#5384ED',
          strokeColor: '#ffffff',
        }

      });

      //tambah listener marker onClick
      google.maps.event.addListener(marker, 'click', function() {
        var infowindow;
        //cek info windows ada apa  kalo ada dikosongin dulu
        if (infowindow) {
          infowindow.setMap(null);
          infowindow = null;
        }

        //init infowindow content
        infowindow = new google.maps.InfoWindow({
          content: info,
          position: coords,
          map: map
        });
      });


      //MENAMPILKAN MARKER KEDALAM MAP
      marker.setMap(map);

    }

    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });

  }
</script>

</html>