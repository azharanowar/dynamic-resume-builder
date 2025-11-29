<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        .wrapper{ width: 80%; margin:0 auto; }
        table tr td:last-child{ width: 120px; }
    </style>
    <script>
        function confirmDelete(id, type) {
            if (confirm("Are you sure you want to delete this entry?")) {
                window.location.href = 'delete.php?type=' + type + '&id=' + id;
            }
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <h2>Admin Dashboard</h2>
        <p>
            <a href="logout.php" class="btn btn-danger">Sign Out</a>
        </p>

        <!-- Experience Section -->
        <div class="container-fluid">
            <h3>Work Experience</h3>
            <a href="add.php?type=experience" class="btn btn-success">Add New Experience</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Period</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id, job_title, company_name, DATE_FORMAT(start_date, '%b %Y') as start, IF(end_date IS NULL, 'Present', DATE_FORMAT(end_date, '%b %Y')) as end FROM experience ORDER BY start_date DESC";
                    if ($result = $mysqli->query($sql)) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['job_title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['company_name']) . "</td>";
                                echo "<td>" . $row['start'] . " - " . $row['end'] . "</td>";
                                echo "<td>";
                                echo '<a href="edit.php?type=experience&id='. $row['id'] .'" class="btn btn-primary">Edit</a> ';
                                echo '<button onclick="confirmDelete('. $row['id'] .', \'experience\')" class="btn btn-danger">Delete</button>';
                                echo "</td>";
                                echo "</tr>";
                            }
                            $result->free();
                        } else {
                            echo '<tr><td colspan="4">No experience records found.</td></tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Education Section -->
        <div class="container-fluid" style="margin-top: 40px;">
            <h3>Education</h3>
            <a href="add.php?type=education" class="btn btn-success">Add New Education</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>Degree</th>
                        <th>Institution</th>
                        <th>Graduation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Similar loop for education can be added here
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>