<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "db_connect.php";

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;

if (!$type || !$id) {
    header("location: admin.php");
    exit;
}

switch ($type) {
    case 'experience':
        $sql = "DELETE FROM experience WHERE id = ?";
        break;
    case 'education':
        $sql = "DELETE FROM education WHERE id = ?";
        break;
    case 'skill':
        $sql = "DELETE FROM skills WHERE id = ?";
        break;
    default:
        // Invalid type, redirect
        header("location: admin.php");
        exit;
}

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$mysqli->close();
header("location: admin.php");
exit();
