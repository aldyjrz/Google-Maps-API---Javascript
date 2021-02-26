<?php
include 'config/database.php';

 
if(isset($_GET['gudang'])){
  $gudang = $_GET['gudang'];
  }else{
    $gudang = 'ALL';
 } 
echo $gudang;
  
if(isset($_POST['no_order'])){
  $no_order = $_POST['no_order'];
  }else{
    $no_order = 'ALL';
  } 
include "koneksinomkot.php";
?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
  <title>Fleet Monitor Indoarsip</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="css/main.css">


</head>

<body>
  <header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="">Fleet Monitor</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </nav>
  </header>
  <main class="container">
    <div class="row">
      <div class="col-md-12">
        <br>
        <div class="card">
        
        <form action="/fleet-monitor/index.php?gudang=".$gudang method="GET">
              <div class="card">
                 
                <label class="col-md-6 col-form-label">KODE GUDANG*</label>
                <div class="col-md-6">
                  <select class="form-control" name='gudang' id="gudang" value='<?=$_POST['gudang'] ?>'> 
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
                  <select class="form-control" name='no_order' id="no_order">
                    <option value="all" >SHOW ALL</option>

                    <?php
                 if(!isset($gudang) || $gudang == 'all'){
                 
                    $log = mysqli_query($con, "select a.no_order as no_order, a.lati as lati, a.longi as longi from tbl_drivergps a JOIN wmt_order_h b ON a.no_order = b.no_order                     where b.read_status ='expedisi/plg' AND b.kodgud='$gudang' and recmod > '2020-01-01' group by no_order");
                   
                 }else{
                  $log = mysqli_query($con, "select a.no_order as no_order, a.lati as lati, a.longi as longi from tbl_drivergps a JOIN wmt_order_h b ON a.no_order = b.no_order where b.read_status ='expedisi/plg' and recmod > '2020-01-01' group by no_order");

                 }
                    while ($data = mysqli_fetch_assoc($log)) {
                 

                      $gd = $data['no_order'];
 

                    ?>

                      <option value="<?php echo $gd ?>"> <?php echo $gd ?></option>

                    <?php } ?>

                  </select>

                  <button class="btn btn-info mb-3 mt-3">CARI LOKASI</button>
                </div>
                
              </div>
          </div>
          </form>
        </div>
      </div>
      <span id="error"></span>
    </div>
    </div>
    <div class="card">
      <div class="card-header">
        <div id="map"></div>
      </div>
    </div>
  </main>

</body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

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
                 if(!isset($gudang) || $gudang == 'all'){
    
                  $marker = mysqli_query($con, "select * from wmt_order_h where status != 'selesai' and  recmod > '2021-01-01';");

                 }else{
                  $marker = mysqli_query($con, "select * from wmt_order_h where status != 'selesai' and kodgud='$gudang' and  recmod > '2021-01-01';");

                 }
 while($data = mysqli_fetch_array($marker)){

    $latitude = $data['lati'];
    $longi = $data['longi'];
    $no_order = $data['no_order'];?>

    
    addMarker(<?php echo $latitude?>, <?php echo $longi?>, '<?php echo $no_order?>'); 

 
<?php 
 }
?>
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
  
</script>

</html>