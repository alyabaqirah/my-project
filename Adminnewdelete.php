<?php
include "pknmdb.php";
$admin_id = $_GET['admin_id'];
$sqldel = "delete from `admins` where `admin_id` = '$admin_id';";
$sqlchk = mysqli_query($pknmgovm_klinik,$sqldel);

if ($sqlchk)
{
    echo "<script>alert('Rekod Admin Berjaya Dihapus');</script>";
    echo "<script>window.location.assign('Adminnewadmin.php');</script>";
}
else {
    echo "<script>alert('Rekod Admin Gagal Dihapus');</script>";
    echo "<script>window.location.assign('Adminnewadmin.php')</script>";
}