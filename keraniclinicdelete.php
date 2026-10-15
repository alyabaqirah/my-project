<?php
include "pknmdb.php";
$bil = $_GET['BIL'];
$sqldel = "delete from `clinics` where `bil` = '$bil';";
$sqlchk = mysqli_query($pknmgovm_klinik,$sqldel);

if ($sqlchk)
{
    echo "<script>alert('Data Klinik Sudah Dihapus');</script>";
    echo "<script>window.location.assign('keranicliniclist.php');</script>";
}
else{
    echo "<script>alert('Data Klinik Gagal Dihapus');</script>";
    echo "<script>window.location.assign('keranicliniclist.php')</script>";
}