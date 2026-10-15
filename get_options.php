<?php
session_start();

include "pknmdb.php"; // Ensure your database connection is working correctly

if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.php");
    exit();
} else {
    session_regenerate_id(true);  // regenerate session ID to prevent session fixation
}

$category = $_GET['category'];  // Get category from the URL

// Define SQL query based on category
if ($category == "staff") {
    $sql = "SELECT BIL, NAMA FROM staff";  
} elseif ($category == "klinik") {
    $sql = "SELECT BIL, NAMA AS KLINIK FROM clinics";  // Fix column alias
} else {
    echo json_encode([]);  // Return empty array if invalid category
    exit();
}

$result = $conn->query($sql);

$options = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $options[] = $row;  // Add each row to the options array
    }
}

// Return options as JSON
echo json_encode($options);

// Close the connection
$conn->close();
?>
