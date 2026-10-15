<?php
include "pknmdb.php";

$newtanggungan = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NO_KAD_PENGENALAN = $_POST['NO_KAD_PENGENALAN'];
    $icfamily = $_POST['icfamily'];
    $relation = strtolower(trim($_POST['relative']));
    $patient = $_POST['patient'];

    $result = $pknmgovm_klinik->query("SELECT MAX(CAST(BIL AS UNSIGNED)) AS max_bil FROM familystaff");
    $row = $result->fetch_assoc();
    $next_bil = $row['max_bil'] ? $row['max_bil'] + 1 : 1; 
    $BIL = $next_bil; 

    $stmt = $pknmgovm_klinik->prepare("INSERT INTO familystaff (BIL, icfamily, patient, NO_KAD_PENGENALAN, relation) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $BIL, $icfamily, $patient, $NO_KAD_PENGENALAN, $relation);

    if ($stmt->execute()) {
        echo "<script>alert('Rekod Telah Diisi');</script>";
        $newtanggungan = [
            'BIL' => $BIL,
            'icfamily' => $icfamily,
            'patient' => $patient,
            'NO_KAD_PENGENALAN' => $NO_KAD_PENGENALAN,
            'relation' => $relation
        ];
        echo "<script>window.location.href = 'keranistaff.php';</script>";
    } else {
        echo "<script>alert('Rekod Tidak Diisi: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

$relationQuery = "SELECT jenis FROM relative";
$relationResult = mysqli_query($pknmgovm_klinik, $relationQuery);
if (!$relationResult) {
    die("Error retrieving relations: " . mysqli_error($pknmgovm_klinik));
}
?>
