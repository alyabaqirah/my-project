<?php
include "pknmdb.php";

if (isset($_POST['icfamily'])) {
    $icfamily = $_POST['icfamily'];

    $sql = "SELECT relation FROM familystaff WHERE icfamily = '$icfamily'";

    $result = mysqli_query($pknmgovm_klinik, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['relation'];
    } else {
        echo "IC tidak dijumpai"; 
    }
}
?>
