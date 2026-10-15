<?php
session_start();
include "pknmdb.php";

if (!isset($_SESSION['username'])) {
    header("Location: keranilogin.php");
    exit();
}

if (isset($_GET['logout'])) {

    session_unset();

    session_destroy();

    header("Location: keranilogin.php");

    exit();
}

$staffQuery = "SELECT NAMA FROM staff";
$staffResult = mysqli_query($pknmgovm_klinik, $staffQuery);

$clinicQuery = "SELECT NAMA_KLINIK FROM clinics";
$clinicResult = mysqli_query($pknmgovm_klinik, $clinicQuery);

?>

<!DOCTYPE html>

<html lang="en">     

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Klinik</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> 

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link href="https://unpkg.com/boxicons/css/boxicons.min.css" rel="stylesheet">

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

        text-transform: uppercase;


    }



    .data-table h2 {

        font-size: 30px;

        margin-top: 40px;

    }



    .data-table table {

        width: 100%;

        border-collapse: collapse;

        margin-top: 20px;
        text-transform: uppercase;

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

    .filter-section {
            padding: 20px;
    
        }
        .filter-section h2 {
            font-size: 30px;
            margin-bottom: 20px;
        }
        .filter-section label {
            display: block;
            margin-top: 10px;
        }
        .filter-section select,
        .filter-section input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .filter-section button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .filter-section button:hover {
            background-color: #0056b3;
        }
        .results {
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        } 
        .filter-section th, .filter-section td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            width: 30%;
}
</style>



<body>



<div class="container">
        <aside class="sidepanel">
            <div class="logo">
                <a href="keranihomepage.php">
                <img src="mcorplogo.png" alt="Logo" class="logo">
                </a>            
            </div>

            <nav class="sidenav">
                <ul>
                    <li><a href="keranihomepage.php">Laman Utama</a></li>
                    <li><a href="keraninewadmin.php">Admin</a></li>
                    <li><a href="keranistaff.php">Senarai Staff</a></li>
                    <li><a href="keranicliniclist.php">Senarai Klinik</a></li>
                    <li><a href="keranitransaksi.php"> Transaksi Baru</a></li>
                    <li><a href="keranitransaksilist.php">Senarai Transaksi</a></li>
                    <li><a href="keranilaporank.php">Laporan Klinik</a></li>
                    <li><a href="keranihomepage.php?logout=true" >Keluar</a></li>
                </ul>
            </nav>
        </aside>

<?php 

// total clinic

$sql = "SELECT COUNT(BIL) AS totclinic FROM clinics";

$result = mysqli_query($pknmgovm_klinik, $sql);

$totclinic = ($result) ? mysqli_fetch_assoc($result)['totclinic'] : 0;



// total transaksi

$sql = "SELECT COUNT(transaction_id) AS tottransaction FROM transactions";

$result = mysqli_query($pknmgovm_klinik, $sql);

$tottransaction = ($result) ? mysqli_fetch_assoc($result)['tottransaction'] : 0;


// total penggunaan bayaran balik

$sql = "SELECT SUM(amount) AS totalb FROM transactions GROUP BY clinic_name";

$result = mysqli_query($pknmgovm_klinik, $sql);

$totalb = ($result) ? mysqli_fetch_assoc($result)['totalb'] : 0;


// total penggunaan klinik

$sql = "SELECT SUM(amount) AS totalc FROM transactions GROUP BY jenis";

$result = mysqli_query($pknmgovm_klinik, $sql);

$totalc = ($result) ? mysqli_fetch_assoc($result)['totalc'] : 0;



// total penggunaan

$sql = "SELECT SUM(amount) AS total FROM transactions";

$result = mysqli_query($pknmgovm_klinik, $sql);

$total = ($result) ? mysqli_fetch_assoc($result)['total'] : 0;




// panggil nama & amount untuk chart

$clinic_transaction_data = [];

$sql = "SELECT clinic_name, jenis, SUM(amount) AS total_amount FROM transactions GROUP BY clinic_name";

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
                    <h1> Senarai Kerani</h1>
                </div>
                <div class="headerright">
                    <i class='bx bx-user'></i> 
                    <span>Selamat Datang, Kerani</span>
                    <a href="keranihomepage.php?logout=true" class="logoutbtn">Keluar</a>
                </div>
            </header>


    <!-- card untuk summary -->

    <div class="cards">

        <div class="card">

            <h3>Jumlah Klinik</h3>

            <p><?php echo $totclinic; ?> Klinik</p>

        </div>

        <div class="card">

            <h3>Jumlah Transaksi</h3>

            <p><?php echo $tottransaction; ?> Transaksi</p>

        </div>

        <div class="card">

            <h3>Jumlah Penggunaan Pendahuluan</h3>

            <p><?php echo $totalb; ?> </p>

        </div>

        <div class="card">

            <h3>Jumlah Penggunaan Klinik</h3>

            <p>RM <?php echo $totalc; ?></p>

        </div>

        <div class="card">

            <h3>Jumlah Penggunaan Keseluruhan</h3>

            <p>RM <?php echo $total; ?></p>

        </div>

    </div>



       <!-- chart -->

    <section class="charts">

    <h2>Statistik</h2>

        <div class="chart-wrapper">

            <div class="chart-container">

                <canvas id="clinicTransaksi"></canvas>

            </div>

        </div>

    </section>

    <?php

    //sorting descending

        usort($clinic_transaction_data, function($a, $b) {

            return $b['total_amount'] - $a['total_amount'];

        });

        // dapat top 5 tertinggi clinics je

        $top_clinic_transaction_data = array_slice($clinic_transaction_data, 0, 5);

        
        ?>

<script>

// klinik chart

var clinicTransaksiCtx = document.getElementById('clinicTransaksi').getContext('2d');

var clinicTransaksiChart = new Chart(clinicTransaksiCtx, {

    type: 'bar',

    data: {

        labels: [<?php foreach ($top_clinic_transaction_data as $clinic) echo "'".strtoupper($clinic['clinic_name']."  ".$clinic['jenis'])."', "; ?>],

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

    </script>

<?php
$category = $name = $startDate = $endDate = "";
$transactions = [];

// Check if the form is submitted and process the filters
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['category'])) {
    // Initialize variables
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    $name = isset($_GET['NAMA']) ? $_GET['NAMA'] : '';
    $startDate = isset($_GET['start-date']) ? $_GET['start-date'] : '';
    $endDate = isset($_GET['end-date']) ? $_GET['end-date'] : '';

    // Prepare query with basic WHERE condition
    $query = "SELECT * FROM transactions WHERE 1=1";

    // Store parameters for binding
    $params = [];

    // Add category filter if provided
    if ($category == 'staff' && !empty($name)) {
        $query .= " AND nama_staff = ?";
        $params[] = $name;
    } elseif ($category == 'klinik' && !empty($name)) {
        $query .= " AND clinic_name = ?";
        $params[] = $name;
    }

    // Add date range filter if provided
    if (!empty($startDate) && !empty($endDate)) {
        $query .= " AND date BETWEEN ? AND ?";
        $params[] = $startDate;
        $params[] = $endDate;
    }

    // Prepare the SQL statement
    $stmt = mysqli_prepare($pknmgovm_klinik, $query);

    // Bind the parameters dynamically based on the number of filters
    if (count($params) > 0) {
        $types = str_repeat('s', count($params)); // Create a type string like "ssss" for four parameters
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Initialize transactions array
    while ($row = mysqli_fetch_assoc($result)) {
        $transactions[] = $row;
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
}
?>

<body>
    <div class="filter-section">
        <h2>Laporan Klinik</h2>

        <form id="filterForm" method="GET" action="">
            <label for="category">Pilih Kategori:</label>
            <select id="category" name="category" onchange="updateDropdown()">
                <option value="" disabled selected>Sila pilih</option>
                <option value="staff" <?php if ($category == 'staff') echo 'selected'; ?>>Staff</option>
                <option value="klinik" <?php if ($category == 'klinik') echo 'selected'; ?>>Klinik</option>
            </select>

            <label for="name-dropdown">Sila Pilih:</label>
            <select id="name-dropdown" name="NAMA" required>
                <option value="" disabled selected>Sila pilih</option>
                <?php
                // Populate the name dropdown based on the selected category
                if ($category == 'staff') {
                    while ($row = mysqli_fetch_assoc($staffResult)) {
                        echo '<option value="' . $row['NAMA'] . '" ' . ($name == $row['NAMA'] ? 'selected' : '') . '>' . $row['NAMA'] . '</option>';
                    }
                } else if ($category == 'klinik') {
                    while ($row = mysqli_fetch_assoc($clinicResult)) {
                        echo '<option value="' . $row['NAMA_KLINIK'] . '" ' . ($name == $row['NAMA_KLINIK'] ? 'selected' : '') . '>' . $row['NAMA_KLINIK'] . '</option>';
                    }
                }
                ?>
            </select>

            <label for="start-date">Tarikh Mula:</label>
            <input type="date" id="start-date" name="start-date" value="<?php echo $startDate; ?>">

            <label for="end-date">Tarikh Tamat:</label>
            <input type="date" id="end-date" name="end-date" value="<?php echo $endDate; ?>">

            <button type="submit">Cari</button>
            <button type="button" onclick="exportToExcel()"><i class='bx bx-download'></i> Eksport ke Excel</button>

        </form>

        <?php if (!empty($transactions)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Transaksi ID</th>
                        <th>Nama Staff/Klinik</th>
                        <th>Tarikh</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Initialize total amount variable
                    $totalAmount = 0;
                    foreach ($transactions as $transaction):
                        // Add the amount of each transaction to the total
                        $totalAmount += $transaction['amount'];
                    ?>
                        <tr>
                            <td><?php echo $transaction['transaction_id']; ?></td>
                            <td><?php echo ($category == 'staff') ? $transaction['nama_staff'] : $transaction['clinic_name']; ?></td>
                            <td><?php echo $transaction['date']; ?></td>
                            <td>RM <?php echo $transaction['amount']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Jumlah Keseluruhan</strong></td>
                        <td><strong>RM <?php echo number_format($totalAmount, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>
    </div>
</body>

<script>
    function updateDropdown() {
        var category = document.getElementById('category').value;
        var nameDropdown = document.getElementById('name-dropdown');
        
        nameDropdown.innerHTML = '<option value="" disabled selected>Sila pilih</option>';

        if (category == 'staff') {
            fetch('get_staff.php')  
                .then(response => response.json())
                .then(data => {
                    data.forEach(item => {
                        var option = document.createElement('option');
                        option.value = item.NAMA;
                        option.textContent = item.NAMA;  
                        nameDropdown.appendChild(option);
                    });
                });
        } else if (category == 'klinik') {
            fetch('get_klinik.php')  
                .then(response => response.json())
                .then(data => {
                    data.forEach(item => {
                        var option = document.createElement('option');
                        option.value = item.NAMA_KLINIK;
                        option.textContent = item.NAMA_KLINIK; 
                        nameDropdown.appendChild(option);
                    });
                });
        }
    }
</script>
</body>

  <!-- transaksi table -->
<section class="data-table">
<h2>Jumlah Transaksi Mengikut Klinik</h2>
<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Klinik</th>
            <th>Pendahuluan</th>
            <th>Jumlah Transaksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $totalAmount = 0;
        $counter = 1;
        
        foreach ($top_clinic_transaction_data as $clinic) {
            $totalAmount += $clinic['total_amount'];
            
            echo "
            <tr>
                <td>{$counter}</td>
                <td>{$clinic['clinic_name']}</td>
                <td>{$clinic['jenis']}</td>
                <td>RM " . number_format($clinic['total_amount'], 2) . "</td>
            </tr>";
            
            $counter++;
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3"><strong>Jumlah Keseluruhan</strong></td>
            <td><strong>RM <?php echo number_format($totalAmount, 2); ?></strong></td>
        </tr>
    </tfoot>
</table>
</section>

<script>

    function exportToExcel() {
        const table = document.querySelector("table"); 
        if (!table) {
            alert("No data to export!");
            return;
        }

        let tableHTML = table.outerHTML;

        //create blob
        const blob = new Blob([tableHTML], { type: 'application/vnd.ms-excel' });

        //link untuk download
        const downloadLink = document.createElement('a');
        downloadLink.href = URL.createObjectURL(blob);
        downloadLink.download = 'Laporan Klinik.xls'; // set kan nama file
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }


</script>
</html>
