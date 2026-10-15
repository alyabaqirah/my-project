<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>upload gambar</title> 
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="NO_KAD_PENGENALAN">IC :</label>
        <input type="text" id="NO_KAD_PENGENALAN" name="NO_KAD_PENGENALAN" required><br><br>
        <label for="PICTURE">Profile Picture:</label>
        <input type="file" id="PICTURE" name="PICTURE" required><br><br>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
<?php
include "pknmdb.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $NO_KAD_PENGENALAN = $_POST['NO_KAD_PENGENALAN'];

    if (isset($_FILES["PICTURE"]) && $_FILES["PICTURE"]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png", "gif" => "image/gif");
        $filename = $_FILES["PICTURE"]["name"];
        $filetype = $_FILES["PICTURE"]["type"];
        $filesize = $_FILES["PICTURE"]["size"];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

        if (in_array($filetype, $allowed)) {
            $sql = "SELECT NO_KAD_PENGENALAN FROM staff WHERE NO_KAD_PENGENALAN = ?";
            $stmt = $pknmgovm_klinik->prepare($sql);
            $stmt->bind_param("s", $NO_KAD_PENGENALAN);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $new_filename = $NO_KAD_PENGENALAN . "_" . $filename;
                $filepath = "image/" . $new_filename;
                if (move_uploaded_file($_FILES["PICTURE"]["tmp_name"], $filepath)) {
                    $sql = "UPDATE staff SET PICTURE = ? WHERE NO_KAD_PENGENALAN = ?";
                    $stmt = $pknmgovm_klinik->prepare($sql);
                    $stmt->bind_param("ss", $filepath, $NO_KAD_PENGENALAN);
                    $stmt->execute();

                    echo "Your file was uploaded successfully.";
                } else {
                    echo "Error: There was a problem uploading your file. Please try again.";
                }
            } else {
                echo "Error: NO KAD PENGENALAN not found.";
            }
        } else {
            echo "Error: There was a problem with the file type.";
        }
    } else {
        echo "Error: " . $_FILES["PICTURE"]["error"];
    }
}

$pknmgovm_klinik->close();
?>
