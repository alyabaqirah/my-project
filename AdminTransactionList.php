<?php
session_start();
include "pknmdb.php";


if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: AdminLogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Senarai Transaksi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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

    .contenttable {
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

        .modal {
            display: none; 
            position: fixed;
            z-index: 1; 
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

      
        h1 {
            color: #333;
            margin-top: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"]{
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: "Poppins", sans-serif;
            font-style: normal;
            font-size: 18px;
            margin-left:2px;
            margin-top:30px;
            cursor: pointer;
        }

        input[type="submit"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: "Poppins", sans-serif;
            font-style: normal;
            font-size: 18px;
            margin-left:5px;
            margin-top:30px;
            cursor: pointer;
        }

        button[type="button"]  {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: "Poppins", sans-serif;
            font-style: normal;
            font-size: 18px;
            margin-left:5px;
            cursor: pointer;
        }

        button[type="submit"]  {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: "Poppins", sans-serif;
            font-style: normal;
            font-size: 18px;
            margin-left:5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-transform: uppercase;

        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }
    /* hide dari website */
    .print-only {
        display: none;
    }
/**hide print */
    .print-title {
        display: none;
        font-size: 25px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    /* ade masa print */
    @media print {
        .print-only {
            display: block;
            margin-top:100px;
        }

        .print-title {
            display: block;
        }

        .sidepanel, .header, form, .logoutbtn, .content {
            display: none;
        }
        .maincontent {
            margin-left: 0;
        }
}

       .filterModal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #filterBtn {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: "Poppins", sans-serif;
            font-style: normal;
            font-size: 18px;
            margin-left:10px;
            cursor: pointer;
        }

        input, select {
            margin-right: 10px;
            padding: 5px;
        }

        button {
            padding: 5px 10px;
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
</head>
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

    <div class="maincontent">

    <div class="print-title">Senarai Transaksi</div>

        <header class="header">
            <div class="headerleft">
                <h1>Senarai Transaksi</h1>
            </div>
            <div class="headerright">
                <i class='bx bx-user'></i> 
                <span>Selamat Datang, Admin</span>
                <a href="AdminHomepage.php?logout=true" class="logoutbtn">Keluar</a>
                </div>
        </header>

        <section class="content">
            <h2>Selamat Datang Ke Senarai Transaksi</h2>
            <p>Di sini anda boleh melihat transaksi yang siap dibuat oleh klinik.</p>
        </section>
        

        <form method="GET" action="">
            <input type="text" id="search" name="search" placeholder="Carian...">
            <button type="button" id="filterBtn"><i class='bx bx-filter-alt'></i></button>
            <input type="submit" value="Cari" />

            <button type="button" onclick="window.print()"><i class='bx bx-printer'></i> Cetak</button>
            <button type="button" onclick="exportToExcel()"><i class='bx bx-download'></i> Eksport ke Excel</button>

            <div id="filterModal" class="filterModal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <form id="filterForm">
                        <label><input type="radio" name="filterType" value="name" checked>Nama Staff</label><br>
                        <label><input type="radio" name="filterType" value="clinic">Nama Klinik</label><br><br>

                        <label for="date">Tarikh :</label>
                        <input type="date" id="date" name="date"><br><br>

                        <button type="submit">Cari</button>

                    </form>
                </div>
            </div>
        </form>

        <section class="contenttable">
            <table>
                <thead>
                    <tr>
                        <th>TRANSAKSI ID</th>
                        <th>NAMA STAFF</th>
                        <th>NAMA PESAKIT</th>
                        <th>HUBUNGAN</th>
                        <th>NAMA KLINIK</th>
                        <th>JENIS PENDAHULUAN</th>
                        <th>JENIS RAWATAN</th>
                        <th>TARIKH</th>
                        <th>DIBUAT PADA</th>
                        <th>JUMLAH</th>
                        <th>BAKI PERUNTUKAN</th>
                        <th>JUMLAH PERLU DIBAYAR</th>                 
                        <th>STATUS</th>
                        <th>Cetak Invois</th>
                        <th>Padam</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "pknmdb.php";

                    $searchQuery = "";
                    if (isset($_GET['search'])) { 
                        $search = $pknmgovm_klinik->real_escape_string($_GET['search']);
                        $searchQuery = "WHERE transactions.nama_staff LIKE '%$search%' OR transactions.clinic_name LIKE '%$search%'";
                    }

                    $sql = "SELECT transactions.*, staff.peruntukan, 
                            (SELECT SUM(amount) FROM transactions WHERE transactions.nama_staff = staff.nama) AS total_spent 
                            FROM transactions 
                            INNER JOIN staff ON transactions.nama_staff = staff.nama 
                            $searchQuery
                            ORDER BY transactions.transaction_id ASC";

                    $result = $pknmgovm_klinik->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $amount = $row['amount'];
                            $peruntukan = $row['peruntukan'];
                            $totalSpent = $row['total_spent'];

                            $balance = $peruntukan - $totalSpent; // calculate remaining balance

                            $updateBalanceSQL = "UPDATE transactions SET balance = ? WHERE transaction_id = ?";
                            $stmt = $pknmgovm_klinik->prepare($updateBalanceSQL);
                            
                            $status = '';
                            if ($balance >= 500) {
                                $status = "<button style='background-color: green; color: white; border: none; border-radius: 15px;'></button>";
                            } elseif ($balance < 500 && $balance >= 200) {
                                $status = "<button style='background-color: yellow; color: black; border: none; border-radius: 15px;'></button>";
                            } elseif ($balance < 200) {
                                $status = "<button style='background-color: red; color: white; border: none; border-radius: 15px;'></button>";
                            }

                            echo "<tr>";
                            echo "<td>" . $row['transaction_id'] . "</td>";
                            echo "<td>" . $row['nama_staff'] . "</td>";
                            echo "<td>" . $row['patient'] . "</td>";
                            echo "<td>" . $row['relation'] . "</td>";
                            echo "<td>" . $row['clinic_name'] . "</td>";
                            echo "<td>" . $row['jenis'] . "</td>";
                            echo "<td>" . $row['rawatan'] . "</td>";
                            echo "<td>" . $row['date'] . "</td>";
                            echo "<td>" . $row['created_at'] . "</td>";
                            echo "<td>RM " . $row['amount'] . "</td>";
                            echo "<td>RM " . number_format($balance, 2) . "</td>"; // display
                            echo "<td>RM "  . number_format($row['extraAmount'], 2) . "</td>";
                            echo "<td>$status</td>";
                            echo "<td>
                            <form action='GenerateInvoiceAdmin.php' method='post' target='_blank'>
                                <input type='hidden' name='NO_KAD_PENGENALAN' value='" . $row['NO_KAD_PENGENALAN'] . "'>
                                <input type='hidden' name='NAMA' value='" . $row['nama_staff'] . "'>
                                <input type='hidden' name='patient' value='" . $row['patient'] . "'>
                                <input type='hidden' name='relative' value='" . $row['relation'] . "'>
                                <input type='hidden' name='NAMA_KLINIK' value='" . $row['clinic_name'] . "'>
                                <input type='hidden' name='jenis' value='" . $row['jenis'] . "'>
                                <input type='hidden' name='rawatan' value='" . $row['rawatan'] . "'>
                                <input type='hidden' name='date' value='" . $row['date'] . "'>
                                <input type='hidden' name='amount' value='" . $row['amount'] . "'>
                                <input type='hidden' name='extraAmount' value='" . $row['extraAmount'] . "'>
                                <button type='submit' class='invoice'>Invois</button>
                            </form>
                          </td>";
                            echo "<td>
                            <button type='button' class='deleteBtn' data-id='{$row['transaction_id']}'>Padam</button>
                          </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Rekod Tidak Dijumpai</td></tr>";
                    }

                    $pknmgovm_klinik->close();
                    ?>
                </tbody>             
            </table>
        </section>
        <section class="content">
            <h2>Status Indicator</h2>
            <p>Hijau - Baki Peruntukan >= RM 500</p>
            <p>Kuning - Baki Peruntukan < RM 500</p>
            <p>Merah - Baki Peruntukan < RM 200</p>
        </section>
        <div class="approved-section print-only">
            <p>Disahkan Oleh :</p>
            <br><br>
            <br><br>
            <p>______________________</p>
            <p>Nama : </p>
            <p>Jawatan : </p>
            <p>Tarikh : </p>
        </div>
    </div>
</div>

 <div id="deleteModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Padam Transaksi</h2>
                <p>Anda pasti ingin memadam transaksi ini?</p>
                <input type="text" id="reason" placeholder="Sebab pemadaman" required>
                <button id="confirmDeleteBtn">Sahkan</button>
            </div>
        </div>
    </div>
</div>

<script>
    const deleteBtns = document.querySelectorAll('.deleteBtn');
    const deleteModal = document.getElementById('deleteModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const closeModal = document.querySelector('.close');
    let currentTransactionId;

    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            currentTransactionId = this.getAttribute('data-id');
            deleteModal.style.display = 'block';
        });
    });

    closeModal.addEventListener('click', function() {
        deleteModal.style.display = 'none';
    });

    confirmDeleteBtn.addEventListener('click', function() {
        const reason = document.getElementById('reason').value;
        if (reason) {
            window.location.href = `Admintransactiondelete.php?transaction_id=${currentTransactionId}&reason=${encodeURIComponent(reason)}`;
        } else {
            alert('Sila berikan sebab untuk pemadaman.');
        }
    });

    window.onclick = function(event) {
        if (event.target == deleteModal) {
            deleteModal.style.display = 'none';
        }
    }

    const filterModal = document.getElementById("filterModal");
    const filterBtn = document.getElementById("filterBtn");
    const closeBtn = document.querySelector(".close");

    filterBtn.onclick = function() {
        filterModal.style.display = "block";
    }

    closeBtn.onclick = function() {
        filterModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == filterModal) {
            filterModal.style.display = "none";
        }
    }

    document.getElementById('filterForm').onsubmit = function(event) {
        event.preventDefault();
        const filterType = document.querySelector('input[name="filterType"]:checked').value;
        const date = document.getElementById('date').value;

        let url = new URL(window.location.href);
        if (filterType && date) {
            url.searchParams.set('filterType', filterType);
            url.searchParams.set('date', date);
        }
        window.location.href = url;
    }

    
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
        downloadLink.download = 'Senarai_Transaksi.xls'; // set kan nama file
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }

</script>

</body>
</html>