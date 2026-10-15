<?php
include "pknmdb.php";

if (isset($_GET['BIL'])) {
    $bil = $_GET['BIL'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama_klinik = $_POST['NAMA_KLINIK'];
        $alamat = $_POST['ALAMAT'];
        $no_telefon = $_POST['NO_TELEFON'];

        $updateSql = "UPDATE clinics SET NAMA_KLINIK='$nama_klinik', ALAMAT='$alamat', NO_TELEFON='$no_telefon' WHERE BIL=$bil";
        if ($pknmgovm_klinik->query($updateSql) === TRUE) {
            echo "Rekod Dikemaskini";
            header("Location: AdminClinicList.php"); 
        } else {
            echo "Ralat Mengemaskini Rekod " . $pknmgovm_klinik->error;
        }
    }

    $sql = "SELECT * FROM clinics WHERE BIL=$bil";
    $result = $pknmgovm_klinik->query($sql); //guna query hantar gi dtbase
    if ($result->num_rows > 0) { // kena letak ni untuk check
        $row = $result->fetch_assoc(); //data masuk dalam row
    } else {
        echo "Rekod Tidak Dijumpai"; // kalau data xde, keluar ni
    }
} else {
    echo "Invalid request";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Kemaskini Klinik</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            font-style: normal;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        input[type="text"] {
            border: none;
            background-color: transparent;
            width: 100%;
            font-family: "Poppins", sans-serif;
            font-style: normal;
            font-size: 18px;
        }

        input[type="text"]:focus {
            outline: none;
            border-bottom: 1px solid #007bff;
        }
        .submit {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
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

<form method="post" action="" enctype="multipart/form-data">
        <table align="center" border="1">
            <tr>
                <td align="center" colspan="2"><h2>Kemaskini Klinik</h2></td>
            </tr>
            <tr>
                <td>BIL :</td>
                <td><?php echo $row['BIL']; ?></td>
            </tr>
            <tr>
                <td>NAMA :</td>
                <td>
                    <input type="text" name="NAMA_KLINIK" value="<?php echo $row['NAMA_KLINIK']; ?>" required />
                </td>
            </tr>
            <tr>
                <td>ALAMAT :</td>
                <td>
                    <input type="text" name="ALAMAT" value="<?php echo $row['ALAMAT']; ?>" required />
                </td>
            </tr>
            <tr>
                <td>NOMBOR TELEFON :</td>
                <td>
                    <input type="text" name="NO_TELEFON" value="<?php echo $row['NO_TELEFON']; ?>" required />
                </td>
            </tr>
            <tr>
            <td colspan="2"><input type="submit" class="submit" value="Kemaskini"></td>
        </tr>
    </table>
</form>

<form method="post" action="AdminClinicList.php">
    <table align="center">
        <tr>
            <td colspan="2"><input type="submit" class="submit" value="Kembali"></td>
        </tr>
    </table>
</form>

</body>
</html>
