<?php
session_start(); //session kena buat kat atas
include "pknmdb.php";

if (!isset($_SESSION['ID'])) {

    header("Location: ClinicLogin.php");

    exit();

} else {

    session_regenerate_id(true);  // regenerate id

}

if (isset($_GET['logout'])) {

    session_unset();

    session_destroy();

    header("Location: ClinicLogin.php");

    exit();
}
$ID = mysqli_real_escape_string($pknmgovm_klinik, $_SESSION['ID']);
$findsql = "SELECT * FROM `clinics` WHERE `ID` = '$ID';";
$chksql = mysqli_query($pknmgovm_klinik, $findsql);


$nama = "Nama tidak dijumpai";

$stmt = $pknmgovm_klinik->prepare("SELECT NAMA_KLINIK FROM clinics WHERE ID = ?");
$stmt->bind_param("s", $ID); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nama = $row['NAMA_KLINIK']; 
} else {
    $nama = "Nama tidak dijumpai";
}

$newTransaction = null;
$maxPeruntukan = 500;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NO_KAD_PENGENALAN = mysqli_real_escape_string($pknmgovm_klinik, $_POST['NO_KAD_PENGENALAN']);
    $nama_staff = mysqli_real_escape_string($pknmgovm_klinik, $_POST['NAMA']);
    $icfamily = mysqli_real_escape_string($pknmgovm_klinik, $_POST['icfamily']);
    $patient = mysqli_real_escape_string($pknmgovm_klinik, $_POST['patient']);
    $age = mysqli_real_escape_string($pknmgovm_klinik, $_POST['age']);
    $relation = strtolower(trim(mysqli_real_escape_string($pknmgovm_klinik, $_POST['relation'])));
    $clinic_name = $_SESSION['NAMA_KLINIK']; 
    $rawatan = mysqli_real_escape_string($pknmgovm_klinik, $_POST['rawatan']);
    $date = mysqli_real_escape_string($pknmgovm_klinik, $_POST['date']);
    $amount = (float)mysqli_real_escape_string($pknmgovm_klinik, $_POST['amount']);


    $relationCheckSQL = "SELECT * FROM familystaff WHERE NO_KAD_PENGENALAN = ? AND icfamily = ?";
    $stmt = $pknmgovm_klinik->prepare($relationCheckSQL);
    $stmt->bind_param("ss", $NO_KAD_PENGENALAN, $icfamily);
    $stmt->execute();
    $relationCheckResult = $stmt->get_result();

    if ($relationCheckResult->num_rows === 0) {
        echo "<script>alert('IC Pesakit tidak berkaitan dengan IC Staff yang dimasukkan.');</script>";
        exit; 
    }


    $extraAmount = 0; 
    $availableAmount = 0;

    if ($relation === "ibu" || $relation === "bapa") {
        // kira total claim ibu ngn bapa
        $query = "SELECT SUM(amount) AS total_claimed 
                  FROM transactions
                  WHERE nama_staff = '$nama_staff' AND (relation = 'ibu' OR relation = 'bapa')";
        $result = mysqli_query($pknmgovm_klinik, $query);
        $row = mysqli_fetch_assoc($result);
        $totalClaimed = (float)$row['total_claimed'];

        if ($totalClaimed >= $maxPeruntukan) {
            $extraAmount = $amount;
            echo "<script>alert('Jumlah Lebihan RM" . number_format($extraAmount, 2) . " perlu dibayar secara tunai/card.');</script>";
            $amount = 0; // kalau amount dah takde dlm peruntukan
        } else {
            $availableAmount = $maxPeruntukan - $totalClaimed;
            if ($availableAmount < $amount) {
                // kira berapa lebih dari peruntukan
                $extraAmount = $amount - $availableAmount;
                echo "<script>alert('Jumlah Lebihan RM" . number_format($extraAmount, 2) . " perlu dibayar secara tunai/card.');</script>";
                $amount = $availableAmount; //deduct only remaining pruntukan
            }
        }
    }
    

    $insertsql = "INSERT INTO transactions (NO_KAD_PENGENALAN, nama_staff, icfamily, patient, age, relation, clinic_name, rawatan, date, amount, extraAmount, created_at) 
                  VALUES ('$NO_KAD_PENGENALAN', '$nama_staff', '$icfamily', '$patient','$age', '$relation', '$clinic_name','$rawatan', '$date', '$amount', '$extraAmount', CURRENT_TIMESTAMP)";

    if ($pknmgovm_klinik->query($insertsql) === TRUE) {
        $newTransaction = [
            'NO_KAD_PENGENALAN' => $NO_KAD_PENGENALAN,
            'nama_staff' => $nama_staff,
            'icfamily' => $icfamily,
            'patient' => $patient,
            'age' => $age,
            'relation' => $relation,
            'clinic_name' => $clinic_name,
            'rawatan' => $rawatan,
            'date' => $date,
            'amount' => $amount,
            'extraAmount' => $extraAmount,
            'created_at' => date('Y-m-d H:i:s')
        ];
    } else {
        echo "Error: " . $pknmgovm_klinik->error;
    }
}

$staffnameQuery = "SELECT NAMA FROM staff";
$staffnameResult = mysqli_query($pknmgovm_klinik, $staffnameQuery);

$relationQuery = "SELECT jenis FROM relative";
$relationResult = mysqli_query($pknmgovm_klinik, $relationQuery);

$clinicQuery = "SELECT NAMA_KLINIK FROM clinics";
$clinicResult = mysqli_query($pknmgovm_klinik, $clinicQuery);


?>
<section class="content">
<h1> Transaksi Baru </h1>

<form method="post" action="" enctype="multipart/form-data">
    <table align="center" border="1">
        <tr>
            <td align="center" colspan="2"><h2>Transaksi Baru</h2></td>
        </tr>
        <tr>
            <td>Nombor Kad Pengenalan:</td>
            <td><input type="text" name="NO_KAD_PENGENALAN" id="ic_input" onblur="fetchName()" required></td>
        </tr>
        <tr>
            <td>Nama Staff:</td>
            <td><input type="text" name="NAMA" id="name_input" required readonly></td>
        </tr>
        <tr>
            <td>Nombor Kad Pengenalan Pesakit:</td>
            <input type="text" name="icfamily" id="icinput" onblur="verifyRelation()" required>
        </tr>
        <tr>
            <td>Nama Pesakit:</td>
            <td><input type="text" name="patient" id="namainput" required readonly></td>
        </tr>
        <tr>
             <td>Umur Pesakit:</td>
             <td><input type="text" name="age" id="age_input" readonly></td>
        </tr>
        <tr>
            <td>Hubungan:</td>
            <td><input type="text" name="relation" id="relation" required readonly></td>
        </tr>
        <tr>
            <td>Klinik:</td>
            <td><input type="text" name="NAMA_KLINIK" value="<?php echo $_SESSION['NAMA_KLINIK']; ?>" readonly></td>
        </tr>
        <tr>
            <td>Jenis Rawatan:</td>
            <td><input type="text" name="rawatan" required></td>
        </tr>
        <tr>
            <td>Jumlah:</td>
            <td><input type="text" name="amount" required></td>
        </tr>
        <tr>
            <td>Tarikh:</td>
            <td><input type="date" name="date" required></td>
        </tr>
        <tr>
            <td colspan="2"><input class="submit" type="submit" value="Submit"></td>
        </tr>
    </table>
</form>
</section>


<script>
// panggil namaa based on ic
function fetchName() {
    var NO_KAD_PENGENALAN = document.getElementById('ic_input').value;

    // untuk confirm kan yang ic tak kosong
    if (NO_KAD_PENGENALAN.trim() === '') {
        alert("Sila Isikan Nombor Kad Pengenalan.");
        return;
    }

    // guna AJAX untuk panggil nama
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_name.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('name_input').value = xhr.responseText;
        }
    };
    xhr.send('NO_KAD_PENGENALAN=' + NO_KAD_PENGENALAN);
}

function verifyRelation() {
    var NO_KAD_PENGENALAN = document.getElementById('ic_input').value; // Staff's IC
    var patientIC = document.getElementById('icinput').value; // Patient's IC

    if (NO_KAD_PENGENALAN.trim() === '' || icfamily.trim() === '') {
        alert("Sila isikan Nombor Kad Pengenalan.");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'verify_relation.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            if (xhr.responseText === 'valid') {
                alert("Hubungan disahkan.");
            } else {
                alert("IC Pesakit tidak berkaitan dengan IC Staff yang dimasukkan.");
                document.getElementById('icinput').value = ''; // Clear invalid entry
            }
        }
    };
    xhr.send('NO_KAD_PENGENALAN=' + NO_KAD_PENGENALAN + '&icfamily=' + icfamily);
}

</script>
</body>
</html>

<?php
mysqli_close($pknmgovm_klinik);
?>
