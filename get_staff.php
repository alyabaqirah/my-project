<?php
include 'pknmdb.php';

$staffQuery = "SELECT NAMA FROM staff"; 
$staffResult = mysqli_query($pknmgovm_klinik, $staffQuery);

$staffList = [];
while ($row = mysqli_fetch_assoc($staffResult)) {
    $staffList[] = $row;
}

echo json_encode($staffList);
?>
