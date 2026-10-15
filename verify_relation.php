<?php
include "pknmdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NO_KAD_PENGENALAN = mysqli_real_escape_string($pknmgovm_klinik, $_POST['NO_KAD_PENGENALAN']);
    $icfamily = mysqli_real_escape_string($pknmgovm_klinik, $_POST['icfamily']);

    $relationCheckSQL = "SELECT * FROM familystaff WHERE NO_KAD_PENGENALAN = ? AND icfamily = ?";
    $stmt = $pknmgovm_klinik->prepare($relationCheckSQL);
    $stmt->bind_param("ss", $NO_KAD_PENGENALAN, $icfamily);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "valid";
    } else {
        echo "invalid";
    }
}
?>
