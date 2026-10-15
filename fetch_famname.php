<?php
include "pknmdb.php";

if (isset($_POST['icfamily']) && isset($_POST['NO_KAD_PENGENALAN'])) {
    $icfamily = mysqli_real_escape_string($pknmgovm_klinik, $_POST['icfamily']);
    $NO_KAD_PENGENALAN = mysqli_real_escape_string($pknmgovm_klinik, $_POST['NO_KAD_PENGENALAN']);

// ni untuk link kan nokadpengenalan staff ngn icfamily

    $sql = "SELECT patient 
            FROM familystaff 
            WHERE icfamily = '$icfamily' 
              AND NO_KAD_PENGENALAN = '$NO_KAD_PENGENALAN'";

    $result = mysqli_query($pknmgovm_klinik, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['patient'];
    } else {
        echo "IC tidak dijumpai atau tidak berkaitan dengan Staff IC"; 
    }
}
?>
