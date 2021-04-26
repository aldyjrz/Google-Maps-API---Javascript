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
<!DOCTYPE html>
<html>
<?php
include "template/head.php";

?>

<?php
?>


<body>
  <main class="container ">
    <div class="row">
      <div class="col-md-12">
        <br>
        <div class="card">

          <form action="/fleet-monitor/index.php" method="get">
            <div class="card">

              <label class="col-md-6 col-form-label">KODE GUDANG*</label>
              <div class="col-md-6">
                <select class="form-control" name='gudang' id="gudang" ?>'>
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
                  echo $dat[0];
                  if ($gudang != 'all') {
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
                       WHERE d.tgl_kirim =  CURRENT_DATE()  AND d.selesai  IS NULL  GROUP BY h.idtransaksi ") or die(mysqli_error($koneksi_1));
                  }

                  while ($data = mysqli_fetch_array($marker)) {
                    $getcoor = mysqli_query($con, "SELECT * from wmt_order_h where no_order ='$data[no_order]'") or die(mysqli_error($con));
                    $get = mysqli_fetch_assoc($getcoor);
                    $order = $data['no_order'];
                    $idtrans = $data['idtransaksi'];
                    $plat = $data['no_mobil'];

                  ?>

                    <option value="<?php echo $order ?>"> <?php echo "  $idtrans - $plat  "; ?></option>

                  <?php } ?>

                </select>

                <button class="btn btn-info mb-3 mt-3">CARI LOKASI</button>
                <?php



                echo "Destination :" . $_GET['lat'] . "<br>";
                ?>
                <hr>

                <span id="distance" class='distance'> </span>
                <span id="estimasi" class='estimasi'> </span>


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
  var order = '<?php echo $gudang ?>';

  $('#gudang option[value=' + order + ']').prop('selected', true);

  var marker;

  var map;
  //inisialisasi awal google maps
  function initMap() {
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer;

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

      var trafficLayer = new google.maps.TrafficLayer();
      directionsDisplay.setMap(map);
      trafficLayer.setMap(map);
      var order = '<?php echo $no_order ?>';

      updateMarker();
      var gd = '<?php echo $gudang ?>';

      $('#gudang option[value=' + gd + ']').prop('selected', true);


      <?php

      if ($_GET['no_order'] != 'all') {

        $getcoor = mysqli_query($con, "SELECT * from wmt_order_h where no_order ='$_GET[no_order]'") or die(mysqli_error($con));
        $get = mysqli_fetch_assoc($getcoor);
      ?>

        console.log("lokasi awal " + <?php echo $get['lati'] ?>);

        calculateAndDisplayRoute(directionsService, directionsDisplay);

      <?php } ?>

    }
    //kondisi jika gagal mendapatkan lokasi terkini
    function failure() {
      console.log("failed get coords");

    }



  }

  function calculateAndDisplayRoute(directionsService, directionsDisplay) {
    const waypts = [];



    var lat = <?php echo $get['lati'] ?>;
    var lng = <?php echo $get['longi'] ?>;

    var lastLocation = new google.maps.LatLng(lat, lng);
    console.log("lokasi awal " + lastLocation);
    var awal = new google.maps.LatLng(-6.40008, 107.444);
    directionsService.route({

      origin: awal,
      destination: lastLocation,
      travelMode: 'DRIVING',
      avoidHighways: false,
      avoidTolls: false
    }, function(response, status) {
      if (status === 'OK') {
        console.log(" status error: " + status);
        var totalDist = 0;
        var totalTime = 0;
        var myroute = response.routes[0];
        for (i = 0; i < myroute.legs.length; i++) {
          totalDist += myroute.legs[i].distance.value;
          totalTime += myroute.legs[i].duration.value;
        }
        totalDist = totalDist / 1000;

        console.log(totalDist);
        console.log(totalTime);
        document.getElementById('distance').innerHTML = "Estimasi Jarak :" + totalDist + " KM <br>  ";
        document.getElementById('estimasi').innerHTML = "Estimasi Waktu Tempuh : " + totalTime / 60 + " Minutes <br>  ";

        directionsDisplay.setDirections(response);
        $("#error").empty();
        $("#error").removeClass();
      } else {
        $("#error").addClass("badge badge-danger");
        $("#error").text("Tidak dapat menemukan nama lokasi, status error: " + status);
        console.log("Tidak dapat menemukan nama lokasi, status error: " + status);

      }
    });
  }


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




    <?php

    if ($no_order == 'all') {
      $marker =  mysqli_query($koneksi_1, "SELECT `h`.`kodgud` AS `kodgud` , `d`.`no_order` AS `no_order` , `d`.`KODPLG` AS `kodplg` , `d`.`layanan` AS `layanan` , `h`.`idtransaksi` AS `idtransaksi` , `h`.`no_mobil` AS `no_mobil` , `h`.`nikdriver` AS `nikdriver` , `h`.`recmod` AS `recmod` , `d`.`selesai` AS `selesai`
      FROM `jadwal_d` `d`
      JOIN `jadwal_h` `h` ON `d`.`idtransaksi` = `h`.`idtransaksi`
      WHERE d.tgl_kirim = CURRENT_DATE( )
      AND d.selesai IS NULL
      GROUP BY h.idtransaksi");
    } else  if ($no_order != 'all') {
      $q = "SELECT `h`.`kodgud` AS `kodgud` , `d`.`no_order` AS `no_order` ,    `d`.`KODPLG` AS `kodplg` , `d`.`layanan` AS `layanan` , `h`.`idtransaksi` AS `idtransaksi` , `h`.`no_mobil` AS `no_mobil` , `h`.`nikdriver` AS `nikdriver` , `h`.`recmod` AS `recmod` , `d`.`selesai` AS `selesai`
      FROM `jadwal_d` `d`
      JOIN `jadwal_h` `h` ON `d`.`idtransaksi` = `h`.`idtransaksi`
      WHERE d.tgl_kirim = CURRENT_DATE( )
      AND d.selesai IS NULL AND d.no_order = '$no_order'
      GROUP BY h.idtransaksi";

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
      $no_order = $data['idtransaksi'];
      $qq =  "SELECT * FROM `login`  WHERE `nik` =   '$nik'";

      $gg = mysqli_query($koneksi_1, $qq);
      $ee = mysqli_fetch_array($gg);
      $nama = $ee['nama_lengkap'];
      $plat = $data['no_mobil'];
      if ($latitude != '') {
    ?>

        addMarker(<?php echo $latitude ?>, <?php echo $longitude ?>, '<?php echo $no_order  ?> - <?php echo  $nama ?> - <?php echo  $plat ?>');



        $("#menu-toggle").click(function(e) {
          e.preventDefault();
          $("#wrapper").toggleClass("toggled");
        });

    <?php
      }
    }
    ?>

  }
</script>

</html>