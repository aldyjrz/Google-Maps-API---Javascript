<?php
include 'config/database.php';
include 'koneksinomkot.php';

if (!isset($_GET['lat'])) {

  echo "<script>alert('Silahkan pilih no-order terlebih dahulu di customer admin');</script>";
}

?>
<main class="container mt-3">
  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-header title">DATA ORDER DAN TUJUAN<a id='close' class='text text-info float-right'>CLOSE / HIDE</a></div>
        <div class="card-body">

          <select class='form-control select2' name='plat'>
            <option value="">-- PILIH MOBIL--</option>
            <?php
            $q  = mysqli_query($con, "SELECT * FROM tbl_m_mobil;");

            while ($mobil = mysqli_fetch_array($q)) {
            ?>


              <option value="<?= $mobil['NO_MOBIL'] ?>"><?= $mobil['KODGUD'] ?> - <?= $mobil['NO_MOBIL'] ?> - <?= $mobil['KAPASITAS'] ?> Box</option>
            <?php } ?>
          </select>
          <button class='btn btn-primary mt-2 mb-2'>BUAT JADWAL D</button><br>

          <?php
          //pisah koma



          $loading_total = 0;
          $dat = explode(",", $_GET['lat']);
          $array[] = "";
          echo "";
          for ($aku = 0; $aku < count($dat); $aku++) {




            //pisah spasi
            $lat = explode(" ", $dat[$aku]);

            //get index
            $order =  $lat[2];
            $estimasi_loading =  $lat[3];
            $loading_total = $loading_total + $estimasi_loading;
            $q = mysqli_query($con, "SELECT * from wmt_order_h where no_order='$order'");
            $data = mysqli_fetch_assoc($q);

            $qq = mysqli_query($con, "SELECT * from pelanggan where KODPLG='$data[kodplg]'");
            $d = mysqli_fetch_assoc($qq);

            array_push($array,  $data['alamat']);



            $x = $estimasi_loading;
            $y = $x % 3600;
            $jam = $x / 60;
            $menit = $y % 60;


          ?>

            <table>
              <tr>
                <td id="ord"><?= $data['no_order']; ?> - <?= floor($jam) ?> Jam <?= floor($menit) ?> Menit</td>
              </tr>

              </tr>


              <td id="address" hidden><?= $data['alamat']; ?></td>


            </table>



          <?php   } ?>
          <hr>

          <hr>
          <a name='rute' id="rute">
          </a>


        </div>

      </div>
    </div>

    <div class="col-md-9">
      <div class="card">
        <div class="card-header"></div>
        <div id='error'></div>

        <div id="mapz" style="   height: 400px;
            margin: 0px;
            padding: 0px">

        </div>
        <div class="card-body">
          <div class="clear-fix"></div>
          <table>
            <tr>
              <td id="distance"> </td>

            </tr>
            <tr>
              <td id="estimasi"> </td>
            </tr>
            <tr>
              <td id="loading"></td>
            </tr>

            <tr>
              <td id="tot"> </td>
            </tr>
          </table>
        </div>

      </div>


    </div>

  </div>

  <div class="col-md-3">
  </div>


  </div>
  </div>
</main>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA9cC9fl_RsrvMZj5v_Sa4W5iSj8C7QZ2Y&callback=initMap"> </script>

<script>
  var order = '<?= $gudang ?>';

  var marker;

  var map;
  //inisialisasi awal google maps
  function initMap() {
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer;
    geocoder = new google.maps.Geocoder();
    // set geolocation dan lokasi terakhir
    var x = navigator.geolocation;
    x.getCurrentPosition(success, failure);
    $('#close').on('click', function(event) {
      $('.card-body').toggle('show');
    });
    //  get current position jika success
    function success(position) {
      var lastLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
      //belum di inisialisi untuk query lokasi terakhir
      map = new google.maps.Map(document.getElementById('mapz'), {
        zoom: 10,
        center: lastLocation

      });
      var trafficLayer = new google.maps.TrafficLayer();
      directionsDisplay.setMap(map);
      trafficLayer.setMap(map);
      <?php $getcoor = mysqli_query($con, "SELECT * from wmt_order_h where no_order ='$_GET[no_order]'") or die(mysqli_error($con));
      $get = mysqli_fetch_assoc($getcoor);
      ?>
      var lat = "";
      var lng = "";
      var address = document.getElementById("address").value;
      geocoder.geocode({
        <?php $address = str_replace("/", " ", $data['alamat']);
        ?> "address": "<?= $address; ?>"

      }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          map.setCenter(results[0].geometry.location);
          map.setZoom(14);
          lat = results[0].geometry.location.lat();
          lng = results[0].geometry.location.lng();
          console.log(results[0].geometry.location.lat());
          calculateAndDisplayRoute(directionsService, directionsDisplay, lat, lng);
          console.log(status);
        } else {
          console.log(status);
          $("#error").addClass("badge badge-danger");
          $("#error").text("Tidak dapat menemukan nama lokasi, status error: " + status);
          console.log("Tidak dapat menemukan nama lokasi, status error: " + status);

        }
      });



    }
    //kondisi jika gagal mendapatkan lokasi terkini
    function failure() {
      console.log("failed get coords");

    }



  }

  function calculateAndDisplayRoute(directionsService, directionsDisplay, latitude, longitude) {

    var items = <?= json_encode($array); ?>;

    var waypoints = [];
    for (var i = 1; i < items.length; i++) {

      var address = items[i];

      if (address !== "") {
        waypoints.push({
          location: address,
          stopover: true
        });
      }
    }


    latitude;
    longitude;

    var lastLocation = new google.maps.LatLng(latitude, longitude);
    var awal = new google.maps.LatLng(-6.40008, 107.444);
    console.log("lokasi awal " + awal);
    console.log("lokasi akhir " + lastLocation);

    directionsService.route({
      waypoints: waypoints,
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
        var infoPanel = document.getElementById('rute');
        var ordetail = document.getElementById('ord');

        for (i = 0; i < myroute.legs.length; i++) {
          var routeSegment = i + 1;
          infoPanel.innerHTML += "<b>Route Segment:" + routeSegment + "</b><br>";
          infoPanel.innerHTML += myroute.legs[i].end_address + '<br>';
          infoPanel.innerHTML += "<b>" + myroute.legs[i].distance.text + '<br> ';

          infoPanel.innerHTML += "<b>" + myroute.legs[i].duration.text + '<br><br><hr>';
          totalDist = totalDist + myroute.legs[i].distance.value;

          totalTime += myroute.legs[i].duration.value;
        }
        totalDist = totalDist / 1000;

        console.log(totalDist);
        console.log(totalTime);
        document.getElementById('distance').innerHTML = "Total Jarak : <b>" + totalDist + " KM <b><br>  ";


        x = totalTime;
        y = x % 3600;
        jam = x / 3600;
        menit = y / 60;
        detik = y % 60;

        z = <?php echo $loading_total ?>;
        console.log("Loading " + z);
        a = z % 3600;
        jams = z / 60;
        menits = jams / 60;
        detiks = jams % 60;

        totalJam = jam + jams;
        totalMenit = menit + menits;
        totalDetik = detik + detiks;
        document.getElementById('estimasi').innerHTML = "Estimasi Waktu <b>" + Math.floor(jam) + ' Jam ' + Math.floor(menit) + ' Menit ' + Math.floor(detik) + ' Detik <b>';
        document.getElementById('loading').innerHTML = "Estimasi Loading <b>" + Math.floor(jams) + ' Jam ' + Math.floor(menits) + ' Menit <br><br>';
        document.getElementById('tot').innerHTML = "TOTAL WAKTU   : <b>" + Math.floor(totalJam) + ' Jam ' + Math.floor(totalMenit) + ' Menit  <b> ' + Math.floor(totalDetik) + ' Detik <b>';


        directionsDisplay.setDirections(response);
        $("#error").empty();
        $("#error").removeClass();
      } else {
        $("#error").addClass("badge badge-danger");
        $("#error").text("Tidak dapat menemukan nama lokasi, status error: " + status);

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
</script>