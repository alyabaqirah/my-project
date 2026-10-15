<?php
session_start();

include "pknmdb.php";

if (!isset($_SESSION['username'])) {

    header("Location: AdminLogin.php");

    exit();

} else {

    session_regenerate_id(true);  // regenerate id

}

function logActivity($userId, $activityType, $details) {
    global $pknmgovm_klinik;
    $userId = escape($userId);
    $activityType = escape($activityType);
    $details = escape($details);

    $sql = "INSERT INTO log_aktiviti (user_id, activity_type, details) VALUES ('$userId', '$activityType', '$details')";
    mysqli_query($pknmgovm_klinik, $sql);
}

if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.php");
    exit();
} 

    session_regenerate_id(true);
    
    
   if (!isset($_SESSION['log_logged'])) {
    logActivity($_SESSION['username'], 'Log Masuk', 'Admin Log Masuk Ke Dalam Sistem'); // Log masuk
    $_SESSION['log_logged'] = true; 
}

function updateStaff($NO_KAD_PENGENALAN, $data) {
    global $pknmgovm_klinik; 

    $sql = "UPDATE staff SET NAMA = '$data' WHERE NO_KAD_PENGENALAN = '$NO_KAD_PENGENALAN'";
    if (mysqli_query($pknmgovm_klinik, $sql)) {
        logActivity($NO_KAD_PENGENALAN, 'Update', 'Kemaskini Maklumat Staff: ' . $NO_KAD_PENGENALAN);
    } else {
        echo "Error updating record: " . mysqli_error($pknmgovm_klinik); // Debugging
    }
}

function deleteStaff($NO_KAD_PENGENALAN) {
    global $pknmgovm_klinik; 

    $sql = "DELETE FROM staff WHERE NO_KAD_PENGENALAN = '$NO_KAD_PENGENALAN'";
    if (mysqli_query($pknmgovm_klinik, $sql)) {
        logActivity($NO_KAD_PENGENALAN, 'Padam', 'Padam Maklumat Staff: ' . $NO_KAD_PENGENALAN);
    } else {
        echo "Error deleting record: " . mysqli_error($pknmgovm_klinik); // Debugging
    }
}

$log_limit = 5; 
if (isset($_GET['view']) && $_GET['view'] === 'all') {
    $log_limit = null; 
}

$log_sql = $log_limit ? "SELECT * FROM log_aktiviti ORDER BY timestamp DESC LIMIT $log_limit" : "SELECT * FROM log_aktiviti ORDER BY timestamp DESC";
$log_result = mysqli_query($pknmgovm_klinik, $log_sql);


if (isset($_GET['logout'])) {
    logActivity($_SESSION['username'], 'Log Keluar', 'Admin Log Keluar Dari Sistem'); // Log log masuk

    session_unset();

    session_destroy();

    header("Location: AdminLogin.php");

    exit();
}

//untuk security

function escape($data) {

    global $pknmgovm_klinik;

    return htmlspecialchars($pknmgovm_klinik->real_escape_string($data));

}

?>

<!DOCTYPE html>

<html lang="en">     

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> 

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


</head>

<style>

    * {

        margin: 0;

        padding: 0;

        box-sizing: border-box;

        font-family: "Poppins", sans-serif;

        font-size: 18px;

    }



    body {

        display: flex;

        min-height: 100vh;

        background-color: #f5f5f5;

    }



    .container {

        display: flex;

        width: 100%;

    }



    .sidepanel {

        width: 250px;

        height: 100vh;

        background-color: #2c3e50;

        padding: 20px;

        color: white;

        position: fixed;

        top: 0;

        left: 0;

        z-index: 1000;

    }



    .sidepanel .logo {

        text-align: center;

        margin-bottom: 30px;

    }



    .sidepanel .logo img {

        width: 120px;

    }



    .sidepanel .sidenav ul {

        list-style: none;

        padding: 0;

    }



    .sidepanel .sidenav ul li {

        margin: 20px 0;

    }



    .sidepanel .sidenav ul li a {

        color: white;

        text-decoration: none;

        font-size: 18px;

        padding: 10px 15px;

        border-radius: 5px;

        display: block;

        transition: background-color 0.3s ease;

    }



    .sidepanel .sidenav ul li a:hover {

        background-color: #3498db;

    }



    .maincontent {

        flex-grow: 1;

        margin-left: 250px;

        padding: 20px;

    }



    .header {

        display: flex;

        justify-content: space-between;

        align-items: center;

        background-color: #fff;

        padding: 20px;

        box-shadow: 0 4px 8px rgba(0,0,0,0.1);

    }



    .headerleft h1 {

        font-size: 24px;

        color: #333;

    }



    .headerright {

        display: flex;

        align-items: center;

        gap: 20px;

    }



    .headerright img {

        width: 40px;

        height: 40px;

        border-radius: 50%;

    }



    .headerright span {

        font-size: 16px;

        color: #333;

    }



    .logoutbtn {

        background-color: #e74c3c;

        color: white;

        padding: 10px 20px;

        border-radius: 5px;

        text-decoration: none;

        transition: background-color 0.3s ease;

    }



    .logoutbtn:hover {

        background-color: #c0392b;

    }



    .content {

        padding: 20px;

        background-color: #fff;

        margin-top: 20px;

        border-radius: 5px;

        box-shadow: 0 4px 8px rgba(0,0,0,0.1);

    }



    .content h2 {

        font-size: 22px;

        color: #333;

        margin-bottom: 20px;

    }



    .content p {

        font-size: 16px;

        color: #666;

    }



    .cards {

        display: grid;

        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));

        gap: 20px;

        margin-top: 40px;



    }



    .card {

        background-color: #fff;

        padding: 20px;

        border-radius: 20px;

        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);

        text-align: center;

    }

    .charts h2 {

        font-size:30px;

        margin-top: 40px;

    }



    .chart-container {

        margin-top: 40px;

        background-color: #fff;

        border-radius: 20px;

        padding: 20px;

        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);

        width: 45%;

    }



    .chart-wrapper {

        display: flex;

        justify-content: space-between;

    }



    .data-table h2 {

        font-size: 30px;

        margin-top: 40px;

    }



    .data-table table {

        width: 100%;

        border-collapse: collapse;

        margin-top: 20px;

    }



    .data-table th, .data-table td {

        padding: 15px;

        text-align: left;

        border-bottom: 1px solid #ddd;

    }



    .widget-container {

        display: grid;

        grid-template-columns: repeat(3, 1fr);

        gap: 20px;

        margin-top: 40px;

    }



    .widget {

        background-color: #fff;

        padding: 15px;

        border-radius: 10px;

        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);

        text-align: center;

    }



    .widget h3 {

        font-size: 1.2rem;

        margin-bottom: 10px;

    }



    .widget p {

        font-size: 1.5rem;

        color: #111;

    }



    .see-all {

    display: flex;

    justify-content: flex-end; 

    margin-top: 20px;

    }



    .see-all-btn {

        background-color: #3498db;

        color: white;

        padding: 10px 20px;

        border-radius: 5px;

        text-decoration: none;

        transition: background-color 0.3s ease;

    }



    .see-all-btn:hover {

        background-color: #2980b9;

    }

    .daftarmasuk {
        color: lightblue;
        margin-bottom :100px;
    }

    .daftar-masuk-container {
    position: fixed; 
    bottom: 20px; 
    left: 20px; 
}

.daftar-masuk {
    background-color: #007BFF; 
    color: white; 
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
}

.dropdown-content {
    display: none; 
    position: absolute;
    background-color: #f9f9f9;
    bottom:100%;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    border-radius: 5px;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #f1f1f1; 
}

.daftar-masuk-container:hover .dropdown-content {
    display: block;
}
@media only screen and (max-width:800px) {
        /* tablets */
        .main {
            width: 80%;
            padding: 0;
        }
        .right {
            width: 100%;
        }
        }
        @media only screen and (max-width:500px) {
        /* phone */
        .menu, .main, .right {
            width: 100%;
        }
    }
</style>



<body>



    <div class="container">

        <aside class="sidepanel">

            <div class="logo">

                <a href="adminHomepage.php">

                <img src="mcorplogo.png" alt="Logo" class="logo">

                </a>            

            </div>

            <nav class="sidenav">

                <ul>

                    <li><a href="AdminHomepage.php">Laman Utama</a></li>

                    <li><a href="adminnewadmin.php">Admin</a></li>

                    <li><a href="Adminstaff.php">Senarai Staff</a></li>

                    <li><a href="AdminClinicList.php">Senarai Klinik</a></li>

                    <li><a href="AdminTransactionList.php">Senarai Transaksi</a></li>

                    <li><a href="AdminLaporanK.php">Laporan Klinik</a></li>

                    <li><a href="AdminHomepage.php?logout=true" >Keluar</a></li>

                </ul>

            </nav>

            <div class="daftar-masuk-container">
            <div class="daftar-masuk"><i class='bx bx-log-in'></i> Daftar Masuk</div>
            <div class="dropdown-content">
                <a href="daftarmasukadmin.php">Daftar Masuk Admin</a>
                <a href="daftarmasukkerani.php">Daftar Masuk Kerani</a>
                <a href="daftarmasukclinic.php">Daftar Masuk Klinik</a>
                <a href="daftarmasukstaff.php">Daftar Masuk Staff</a>
            </div>
        </div>

        </aside>

<?php 

// total admin

$sql = "SELECT COUNT(admin_id) AS totadmin FROM admins";

$result = mysqli_query($pknmgovm_klinik, $sql);

$totadmin = ($result) ? mysqli_fetch_assoc($result)['totadmin'] : 0;

// total kerani 

$sql = "SELECT COUNT(kerani_id) AS totkerani FROM kerani";

$result = mysqli_query($pknmgovm_klinik, $sql);

$totkerani = ($result) ? mysqli_fetch_assoc($result)['totkerani'] : 0;


//total staff

$sql = "SELECT COUNT(BIL) AS totstaff FROM staff";

$result = mysqli_query($pknmgovm_klinik, $sql);

$totstaff = ($result) ? mysqli_fetch_assoc($result)['totstaff'] : 0;



// total clinic

$sql = "SELECT COUNT(BIL) AS totclinic FROM clinics";

$result = mysqli_query($pknmgovm_klinik, $sql);

$totclinic = ($result) ? mysqli_fetch_assoc($result)['totclinic'] : 0;



// total transaksi

$sql = "SELECT COUNT(transaction_id) AS tottransaction FROM transactions";

$result = mysqli_query($pknmgovm_klinik, $sql);

$tottransaction = ($result) ? mysqli_fetch_assoc($result)['tottransaction'] : 0;



// total penggunaan

$sql = "SELECT SUM(amount) AS total FROM transactions";

$result = mysqli_query($pknmgovm_klinik, $sql);

$total = ($result) ? mysqli_fetch_assoc($result)['total'] : 0;




// panggil nama & amount untuk chart

$clinic_transaction_data = [];

$sql = "SELECT clinic_name, SUM(amount) AS total_amount FROM transactions GROUP BY clinic_name";

$result = mysqli_query($pknmgovm_klinik, $sql);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $clinic_transaction_data[] = $row;

    }

}

$staff_transaction_data = [];

$sql = "SELECT nama_staff, SUM(amount) AS total_amount FROM transactions GROUP BY nama_staff";

$result = mysqli_query($pknmgovm_klinik, $sql);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $staff_transaction_data[] = $row;

    }

}

?>



<div class="maincontent">

<header class="header">

                <div class="headerleft">

                    <h1> Dashboard Admin</h1>

                </div>

                <div class="headerright">

                    <i class='bx bx-user'></i> 

                    <span>Selamat Datang, Admin</span>

                    <a href="AdminHomepage.php?logout=true" class="logoutbtn">Keluar</a>

                </div>

            </header>



            <section class="content">

                <h2>Selamat Datang Ke Admin</h2>

                <p>Di sini anda boleh mengurus pengguna, urus niaga dan tetapan lain.</p>

            </section>



    <!-- card untuk summary -->

    <div class="cards">

        <div class="card">

            <h3>Jumlah Admin</h3>

            <p><?php echo $totadmin; ?> Admin</p>

        </div>

        <div class = "card">
            <h3> Jumlah Kerani </h3>

            <p><?php echo $totkerani; ?> Kerani</p>

        </div>

        <div class="card">

            <h3>Jumlah Staff</h3>

            <p><?php echo $totstaff; ?> Staff</p>

        </div>

        <div class="card">

            <h3>Jumlah Klinik</h3>

            <p><?php echo $totclinic; ?> Klinik</p>

        </div>

        <div class="card">

            <h3>Jumlah Transaksi</h3>

            <p><?php echo $tottransaction; ?> Transaksi</p>

        </div>

        <div class="card">

            <h3>Jumlah Penggunaan</h3>

            <p>RM <?php echo $total; ?></p>

        </div>

    </div>



       <!-- chart -->

       <section class="charts">

        <h2>Statistik</h2>

        <div class="chart-wrapper">

            <div class="chart-container">

                <canvas id="transaksi"></canvas>

            </div>

            <div class="chart-container">

                <canvas id="clinicTransaksi"></canvas>

            </div>

        </div>

        <div class="chart-container">

            <canvas id="staffTransaksi"></canvas>

        </div>

    </section>



    <?php

    //sorting descending

        usort($clinic_transaction_data, function($a, $b) {

            return $b['total_amount'] - $a['total_amount'];

        });



        // dapat top 5 tertinggi clinics je

        $top_clinic_transaction_data = array_slice($clinic_transaction_data, 0, 5);



        //sorting descending

        usort($staff_transaction_data, function($a, $b) {

            return $b['total_amount'] - $a['total_amount'];

        });



        // panggil top 5 staff

        $top_staff_transaction_data = array_slice($staff_transaction_data, 0, 5);

        ?>



<script>

// total pie admin

var ctx = document.getElementById('transaksi').getContext('2d');

var transaksiChart = new Chart(ctx, {

    type: 'line',

    data: {

        labels: ['Admin', 'Staff', 'Klinik', 'Transaksi', 'Kerani'],

        datasets: [{

            label: 'Jumlah',

            data: [<?php echo $totadmin; ?>, <?php echo $totstaff; ?>, <?php echo $totclinic; ?>, <?php echo $tottransaction; ?>,  <?php echo $totkerani; ?>],

            backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(54, 162, 235, 0.2)'],

            borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)', 'rgba(54, 162, 235, 1)'],

            borderWidth: 1

        }]

    },

    options: {

        responsive: true

    }

});



// klinik chart

var clinicTransaksiCtx = document.getElementById('clinicTransaksi').getContext('2d');

var clinicTransaksiChart = new Chart(clinicTransaksiCtx, {

    type: 'bar',

    data: {

        labels: [<?php foreach ($top_clinic_transaction_data as $clinic) echo "'".$clinic['clinic_name']."', "; ?>],

        datasets: [{

            label: 'Jumlah Transaksi (RM)',

            data: [<?php foreach ($top_clinic_transaction_data as $clinic) echo $clinic['total_amount'].", "; ?>],

            backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],

            borderColor: 'rgba(54, 162, 235, 1)',

            borderWidth: 1

        }]

    },

    options: {

        responsive: true

    }

});





// staff transaksi chart

var staffTransaksiCtx = document.getElementById('staffTransaksi').getContext('2d');

var staffTransaksiChart = new Chart(staffTransaksiCtx, {

    type: 'pie',

    data: {

        labels: [<?php foreach ($top_staff_transaction_data as $staff) echo "'".$staff['nama_staff']."', "; ?>],

        datasets: [{

            label: 'Jumlah Transaksi (RM)',

            data: [<?php foreach ($top_staff_transaction_data as $staff) echo $staff['total_amount'].", "; ?>],

            backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],

            borderColor: 'rgba(153, 102, 255, 1)',

            borderWidth: 1

        }]

    },

    options: {

        responsive: true

    }

});

    </script>



    <!-- transaksi table -->

    <section class="data-table">

            <h2>Transaksi Terkini</h2>

            <table>
                <thead>

                    <tr>

                        <th>Tarikh</th>

                        <th>Nama</th>

                        <th>Jumlah</th>

                        <th>Baki Peruntukan</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $result = mysqli_query($pknmgovm_klinik, "SELECT * FROM transactions ORDER BY date DESC LIMIT 10");

                    while ($row = mysqli_fetch_assoc($result)) {

                        echo "

                        <tr>

                            <td>{$row['date']}</td>

                            <td>{$row['nama_staff']}</td>

                            <td>RM {$row['amount']}</td>

                            <td>RM {$row['balance']}</td>

                        </tr>";

                    }

                    ?>

                </tbody>

            </table>

            <div class="see-all">

                <a href="AdminTransactionList.php" class="see-all-btn">Lihat Semua</a>

            </div>

    </section>
    <section class="data-table">
    <h2>Log Aktiviti</h2>
    <table>
        <thead>
            <tr>
                <th>Jenis</th>
                <th>Jenis Aktiviti</th>
                <th>Butiran</th>  
                <th>Timestamp</th>

            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($log_result)) { ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['activity_type']; ?></td>
                    <td><?php echo $row['details']; ?></td>
                    <td><?php echo $row['timestamp']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="see-all">
        <?php if (isset($_GET['view']) && $_GET['view'] === 'all') { ?>
            <a href="?" class="see-all-btn">Kembali</a>
        <?php } else { ?>
            <a href="?view=all" class="see-all-btn">Lihat Semua</a>
        <?php } ?>
    </div>
</section>

</body>
</html>