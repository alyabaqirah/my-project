<?php

include "pknmdb.php";

if (isset($_GET['BIL'])) {
    $bil = $_GET['BIL'];

    $sql = "SELECT * FROM staff WHERE BIL=?";
    $stmt = $pknmgovm_klinik->prepare($sql);
    $stmt->bind_param("i", $bil);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = $_POST['NAMA'];
        $no_kad_pengenalan = $_POST['NO_KAD_PENGENALAN'];
        $peruntukan = $_POST['PERUNTUKAN']; 

        $sql = "UPDATE staff SET NAMA=?, NO_KAD_PENGENALAN=?, PERUNTUKAN=? WHERE BIL=?";
        echo "Rekod Dikemaskini";
        header("Location: Adminstaff.php");
        $stmt = $pknmgovm_klinik->prepare($sql);
        $stmt->bind_param("sssi", $nama, $no_kad_pengenalan, $peruntukan, $bil);
        $stmt->execute();

        if (isset($_FILES["PICTURE"]) && $_FILES["PICTURE"]["error"] === 0) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png", "gif" => "image/gif");
            $filename = $_FILES["PICTURE"]["name"];
            $filetype = $_FILES["PICTURE"]["type"];
            $filesize = $_FILES["PICTURE"]["size"];

            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $allowed)) die("Ralat: Format fail ini tidak boleh.");

            $maxsize = 5 * 1024 * 1024; // 5MB
            if ($filesize > $maxsize) die("Ralat: Saiz fail besar dari limit.");

            if (in_array($filetype, $allowed)) {
                $new_filename = $no_kad_pengenalan . "_" . $filename;
                $filepath = "uploads/" . $new_filename;

                if (move_uploaded_file($_FILES["PICTURE"]["tmp_name"], $filepath)) {
                    $sql = "UPDATE staff SET PICTURE=? WHERE BIL=?";
                    $stmt = $pknmgovm_klinik->prepare($sql);
                    $stmt->bind_param("si", $filepath, $bil);
                    $stmt->execute();
                    echo "Gambar Telah Berjaya Diupload.";
                    echo "Rekod Dikemaskini";
                    header("Location: Adminstaff.php");
                    exit;
                } else {
                    echo "Ralat: Cuba Lagi.";
                }
            } else {
                echo "Ralat: Ada Masalah dengan Fail.";
            }
        } else if ($_FILES["PICTURE"]["error"] !== UPLOAD_ERR_NO_FILE) {
            echo "Error: " . $_FILES["PICTURE"]["error"];
        }

        $stmt->close();
    }
}
$pknmgovm_klinik->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Staff</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
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
        input[type="text"] {
            border: none;
            background-color: transparent;
            width: 100%;
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
        } @media only screen and (max-width:800px) {
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
            <td align="center" colspan="2"><h2>Kemaskini Staff</h2></td>
        </tr>
        <tr>
            <td>BIL :</td>
            <td><?php echo $row['BIL']; ?></td>
        </tr>
        <tr>
            <td>GAMBAR :</td>
            <td>
                <?php if (!empty($row['PICTURE'])): ?>
                    <img src="<?php echo $row['PICTURE']; ?>" alt="Profile Picture" width="100" height="100"><br>
                <?php endif; ?>
                <input type="file" name="PICTURE">
            </td>
        </tr>
        <tr>
            <td>NAMA :</td>
            <td><input type="text" name="NAMA" value="<?php echo $row['NAMA']; ?>" required></td>
        </tr>
        <tr>
            <td>NO KAD PENGENALAN :</td>
            <td><input type="text" name="NO_KAD_PENGENALAN" value="<?php echo $row['NO_KAD_PENGENALAN']; ?>" required></td>
        </tr>
        <tr>
            <td>PERUNTUKAN :</td>
            <td><input type="text" name="PERUNTUKAN" value="<?php echo $row['PERUNTUKAN']; ?>" required></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" class="submit" value="Kemaskini"></td>
        </tr>
    </table>
</form>

<form method="post" action="Adminstaff.php">
    <table align="center">
        <tr>
            <td colspan="2"><input type="submit" class="submit" value="Kembali"></td>
        </tr>
    </table>
</form>

</body>
</html>
