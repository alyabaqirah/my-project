<?php

// ni cara kiraan birthday based on ic. 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NO_KAD_PENGENALAN = $_POST['NO_KAD_PENGENALAN'];

    $birthYear = substr($NO_KAD_PENGENALAN, 0, 2);
    $birthMonth = substr($NO_KAD_PENGENALAN, 2, 2);
    $birthDay = substr($NO_KAD_PENGENALAN, 4, 2);

    $currentYear = date('Y');
    $currentCentury = intval(substr($currentYear, 0, 2));
    $birthYearFull = ($birthYear > substr($currentYear, 2)) ? ($currentCentury - 1) . $birthYear : $currentCentury . $birthYear;

    $birthDate = DateTime::createFromFormat('Y-m-d', "$birthYearFull-$birthMonth-$birthDay");
    $today = new DateTime();
    $age = $birthDate ? $today->diff($birthDate)->y : "Invalid IC format";

    echo $age;
}
?>
