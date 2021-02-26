<?php
       
       $userDB_server = "192.168.200.33";
       $userDB_user = "root";
       $userDB_password = "P@ssw0rd";
       $userDB_database = "dbarsip";
       $con = mysqli_connect("$userDB_server","$userDB_user","$userDB_password") or die ('Unable to establish a DB connection');
       $userDB = mysqli_select_db($con, "$userDB_database") or die ('Unable to establish a DB connection');
       
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['tgl_kirim']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($con,$_POST['search']['value']); // Search value

## Date search value
$searchByFromdate = mysqli_real_escape_string($con,$_POST['searchByFromdate']);
$searchByTodate = mysqli_real_escape_string($con,$_POST['searchByTodate']);

## Search 
$searchQuery = " ";
if($searchValue != ''){
    $searchQuery = " and (no_sjtt like '%".$searchValue."%' or no_order like '%".$searchValue."%' or nikdriver like'%".$searchValue."%' ) ";
}

// Date filter
if($searchByFromdate != '' && $searchByTodate != ''){
    $searchQuery .= " and (tgl_kirim between '".$searchByFromdate."' and '".$searchByTodate."' ) ";
}

## Total number of records without filtering
$sel = mysqli_query($con,"SELECT COUNT(*) FROM jadwal_d a JOIN jadwal_h b ON a.idtransaksi=b.idtransaksi ");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$sel = mysqli_query($con,"SELECT * FROM jadwal_d a JOIN jadwal_h b ON a.idtransaksi=b.idtransaksi  WHERE 1 ".$searchQuery);

$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
 
 
## Fetch records
$empQuery = "SELECT * FROM jadwal_d a JOIN jadwal_h b ON a.idtransaksi=b.idtransaksi";
$empRecords = mysqli_query($con, $empQuery);
$data = array();
 

while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
    	"id"=>$row['id'],
    	"nikdriver"=>$row['nikdriver'],
    	"no_sjtt"=>$row['no_sjtt'],
    	"no_order"=>$row['no_order'],
    	"tgl_kirim"=>$row['tgl_kirim']
    );
  
}
echo $data;
## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "Data" => $data
);


echo json_encode($response);
die;