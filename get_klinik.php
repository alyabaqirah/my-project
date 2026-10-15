<?php
include 'pknmdb.php';

$clinicQuery = "SELECT NAMA_KLINIK FROM clinics"; 
$clinicResult = mysqli_query($pknmgovm_klinik, $clinicQuery);

$clinicList = [];
while ($row = mysqli_fetch_assoc($clinicResult)) {
    $clinicList[] = $row;
}

echo json_encode($clinicList);
?>
