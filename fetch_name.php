
<?php
include "pknmdb.php";

if (isset($_POST['NO_KAD_PENGENALAN'])) {
    $staffIC = mysqli_real_escape_string($pknmgovm_klinik, $_POST['NO_KAD_PENGENALAN']);
    $query = "SELECT NAMA FROM staff WHERE NO_KAD_PENGENALAN = ?";
    $stmt = $pknmgovm_klinik->prepare($query);
    $stmt->bind_param("s", $staffIC);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['NAMA'];
    } else {
        echo "Staff tidak wujud";
    }
}
?>
