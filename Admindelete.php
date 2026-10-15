<?php

include "pknmdb.php";

$bil = $_GET['BIL'];

$sqldel = "delete from `staff` where `bil` = '$bil';";

$sqlchk = mysqli_query($pknmgovm_klinik,$sqldel);

if ($sqlchk)

{

    echo "<script>alert('Rekod Staff Berjaya Dihapus');</script>";

    echo "<script>window.location.assign('Adminstaff.php');</script>";

}

else {

    echo "<script>alert('Rekod Staff Gagal Dihapus');</script>";

    echo "<script>window.location.assign('AdminStaff.php')</script>";
}  