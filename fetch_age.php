<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $icfamily = $_POST['icfamily'];

    $birthYear = substr($icfamily, 0, 2);
    $birthMonth = substr($icfamily, 2, 2);
    $birthDay = substr($icfamily, 4, 2);

    $currentYear = date('Y');
    $currentCentury = intval(substr($currentYear, 0, 2));
    $birthYearFull = ($birthYear > substr($currentYear, 2)) ? ($currentCentury - 1) . $birthYear : $currentCentury . $birthYear;

    $birthDate = DateTime::createFromFormat('Y-m-d', "$birthYearFull-$birthMonth-$birthDay");
    $today = new DateTime();
    $age = $birthDate ? $today->diff($birthDate)->y : "Invalid IC format";

    echo $age;
}
?>
