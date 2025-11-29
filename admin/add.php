<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "db_connect.php";

$type = $_GET['type'] ?? '';
$page_title = "Add New " . ucfirst($type);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($type) {
        case 'experience':
            $sql = "INSERT INTO experience (job_title, company_name, start_date, end_date, description) VALUES (?, ?, ?, ?, ?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("sssss", $job_title, $company_name, $start_date, $end_date, $description);
                $job_title = $_POST['job_title'];
                $company_name = $_POST['company_name'];
                $start_date = $_POST['start_date'];
                $end_date = empty($_POST['end_date']) ? null : $_POST['end_date'];
                $description = $_POST['description'];
                $stmt->execute();
            }
            break;
        case 'education':
            $sql = "INSERT INTO education (degree, institution, graduation_year) VALUES (?, ?, ?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ssi", $degree, $institution, $graduation_year);
                $degree = $_POST['degree'];
                $institution = $_POST['institution'];
                $graduation_year = $_POST['graduation_year'];
                $stmt->execute();
            }
            break;
        case 'skill':
            $sql = "INSERT INTO skills (skill_name, proficiency) VALUES (?, ?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("si", $skill_name, $proficiency);
                $skill_name = $_POST['skill_name'];
                $proficiency = $_POST['proficiency'];
                $stmt->execute();
            }
            break;
    }

    if (isset($stmt)) {
        $stmt->close();
    }
    $mysqli->close();
    header("location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="wrapper">
        <h2><?php echo $page_title; ?></h2>
        <form action="add.php?type=<?php echo htmlspecialchars($type); ?>" method="post">
            <?php if ($type == 'experience'): ?>
                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" name="job_title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" name="company_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>End Date (leave blank for present)</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="5"></textarea>
                </div>
            <?php elseif ($type == 'education'): ?>
                <div class="form-group">
                    <label>Degree</label>
                    <input type="text" name="degree" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Institution</label>
                    <input type="text" name="institution" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Graduation Year</label>
                    <input type="number" name="graduation_year" class="form-control" min="1900" max="2099" step="1" required>
                </div>
            <?php elseif ($type == 'skill'): ?>
                <div class="form-group">
                    <label>Skill Name</label>
                    <input type="text" name="skill_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Proficiency (%)</label>
                    <input type="range" name="proficiency" class="form-control" min="1" max="100" value="75" required>
                </div>
            <?php else: ?>
                <p>Invalid type specified.</p>
            <?php endif; ?>

            <?php if (in_array($type, ['experience', 'education', 'skill'])): ?>
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="admin.php" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
