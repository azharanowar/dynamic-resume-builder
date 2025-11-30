<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once 'includes/db_connect.php';
?>

    <?php include 'includes/header.php'; ?>
    <script>
        function confirmDelete(id, type) {
            if (confirm("Are you sure you want to delete this entry?")) {
                window.location.href = 'delete.php?type=' + type + '&id=' + id;
            }
        }
    </script>
    <div class="wrapper">
        <h2>Admin Dashboard</h2>
        <p>
            <a href="logout.php" class="btn btn-danger">Log Out</a>
        </p>

        <?php
        // Display feedback message if it exists in the session
        if (isset($_SESSION['message']) && is_array($_SESSION['message'])) {
            $message_text = $_SESSION['message']['text'];
            $message_type = $_SESSION['message']['type']; // 'success' or 'error'
            // Use the type to set the class for styling
            echo "<div class='message " . htmlspecialchars($message_type) . "'>" . htmlspecialchars($message_text) . "</div>";
            unset($_SESSION['message']); // Clear the message after displaying it
        }
        ?>

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
                                echo '<a href="edit.php?type=experience&id='. $row['id'] .'" class="btn btn-primary" style="float:left">Edit</a> ';
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
                        <th>Period</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id, degree, institution, DATE_FORMAT(start_date, '%b %Y') as start, IF(graduation_date IS NULL, 'Present', DATE_FORMAT(graduation_date, '%b %Y')) as grad_date FROM education ORDER BY start_date DESC";
                    if ($result = $mysqli->query($sql)) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $period = $row['start'] . ' - ' . $row['grad_date'];
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['degree']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['institution']) . "</td>";
                                echo "<td>" . $period . "</td>";
                                echo "<td>";
                                echo '<a href="edit.php?type=education&id='. $row['id'] .'" class="btn btn-primary" style="float:left">Edit</a> ';
                                echo '<button onclick="confirmDelete('. $row['id'] .', \'education\')" class="btn btn-danger">Delete</button>';
                                echo "</td>";
                                echo "</tr>";
                            }
                            $result->free();
                        } else {
                            echo '<tr><td colspan="4">No education records found.</td></tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Skills Section -->
        <div class="container-fluid" style="margin-top: 40px;">
            <h3>Skills</h3>
            <a href="add.php?type=skill" class="btn btn-success">Add New Skill</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>Skill Name</th>
                        <th>Proficiency</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id, name, proficiency FROM skills ORDER BY proficiency DESC";
                    if ($result = $mysqli->query($sql)) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . $row['proficiency'] . "%</td>";
                                echo "<td>";
                                echo '<a href="edit.php?type=skill&id='. $row['id'] .'" class="btn btn-primary" style="float:left">Edit</a> ';
                                echo '<button onclick="confirmDelete('. $row['id'] .', \'skill\')" class="btn btn-danger">Delete</button>';
                                echo "</td>";
                                echo "</tr>";
                            }
                            $result->free();
                        } else {
                            echo '<tr><td colspan="3">No skills found.</td></tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>