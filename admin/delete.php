<?php
session_start();
require_once 'includes/db_connect.php';

// --- SECURITY: Ensure user is logged in ---
// You should have a check here to make sure only authenticated admins can delete.
// For example:
// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//     header("Location: login.php");
//     exit;
// }

// 1. Validate that 'type' and 'id' are provided
if (!isset($_GET['type']) || !isset($_GET['id'])) {
    $_SESSION['message'] = ['text' => 'Error: Invalid delete request.', 'type' => 'error'];
    header("Location: admin.php"); // Redirect to the main admin page
    exit;
}

$type = $_GET['type'];
$id = $_GET['id'];

// 2. Validate the 'id' is a number
if (!filter_var($id, FILTER_VALIDATE_INT)) {
    $_SESSION['message'] = ['text' => 'Error: Invalid ID format.', 'type' => 'error'];
    header("Location: admin.php");
    exit;
}

// 3. Whitelist the table name to prevent arbitrary table deletion
$allowed_types = [
    'education' => 'education',
    'experience' => 'experience',
    'skill' => 'skills' // Allow 'skill' from the URL and map it to the 'skills' table
];

if (!array_key_exists($type, $allowed_types)) {
    $_SESSION['message'] = ['text' => 'Error: Invalid section specified for deletion.', 'type' => 'error'];
    header("Location: admin.php");
    exit;
}

$table_name = $allowed_types[$type]; // Get the correct table name from our whitelist

// 4. Prepare and execute the DELETE statement
$stmt = $mysqli->prepare("DELETE FROM `$table_name` WHERE id = ?");
if ($stmt === false) {
    $_SESSION['message'] = ['text' => 'Error preparing statement: ' . $mysqli->error, 'type' => 'error'];
    header("Location: admin.php");
    exit;
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['message'] = ['text' => ucfirst($table_name) . ' entry deleted successfully!', 'type' => 'success'];
} else {
    $_SESSION['message'] = ['text' => 'Error deleting entry: ' . $stmt->error, 'type' => 'error'];
}

$stmt->close();
$mysqli->close();

// 5. Redirect back to the management page
header("Location: admin.php");
exit;
