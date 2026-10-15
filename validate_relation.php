<?php
include "pknmdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $icfamily = mysqli_real_escape_string($pknmgovm_klinik, $_POST['icfamily']);
    $NO_KAD_PENGENALAN = mysqli_real_escape_string($pknmgovm_klinik, $_POST['NO_KAD_PENGENALAN']);

    $query = "SELECT jenis FROM familystaff WHERE NO_KAD_PENGENALAN = ? AND icfamily = ?";
    $stmt = $pknmgovm_klinik->prepare($query);
    $stmt->bind_param("ss", $NO_KAD_PENGENALAN, $icfamily);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['valid' => true, 'relation' => $row['jenis']]);
    } else {
        echo json_encode(['valid' => false]);
    }
    exit;
}
?>
