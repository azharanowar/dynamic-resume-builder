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

$page_title = "Edit " . ucfirst($type);
$data = [];

// Fetch existing data
switch ($type) {
    case 'experience':
        $sql = "SELECT * FROM experience WHERE id = ?";
        break;
    case 'education':
        $sql = "SELECT * FROM education WHERE id = ?";
        break;
    case 'skill':
        $sql = "SELECT * FROM skills WHERE id = ?";
        break;
    default:
        header("location: admin.php");
        exit;
}

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $data = $result->fetch_assoc();
        } else {
            // No record found
            header("location: admin.php");
            exit;
        }
    }
    $stmt->close();
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($type) {
        case 'experience':
            $sql = "UPDATE experience SET job_title = ?, company_name = ?, start_date = ?, end_date = ?, description = ? WHERE id = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("sssssi", $job_title, $company_name, $start_date, $end_date, $description, $id);
                $job_title = $_POST['job_title'];
                $company_name = $_POST['company_name'];
                $start_date = $_POST['start_date'];
                $end_date = empty($_POST['end_date']) ? null : $_POST['end_date'];
                $description = $_POST['description'];
                $id = $_POST['id'];
                $stmt->execute();
            }
            break;
        case 'education':
            $sql = "UPDATE education SET degree = ?, institution = ?, graduation_year = ? WHERE id = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ssii", $degree, $institution, $graduation_year, $id);
                $degree = $_POST['degree'];
                $institution = $_POST['institution'];
                $graduation_year = $_POST['graduation_year'];
                $id = $_POST['id'];
                $stmt->execute();
            }
            break;
        case 'skill':
            $sql = "UPDATE skills SET skill_name = ?, proficiency = ? WHERE id = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("sii", $skill_name, $proficiency, $id);
                $skill_name = $_POST['skill_name'];
                $proficiency = $_POST['proficiency'];
                $id = $_POST['id'];
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
    <style>
        .wrapper { width: 80%; margin:0 auto; }
        .btn-primary { width: auto; }
        .btn-primary, .btn-secondary { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2><?php echo $page_title; ?></h2>
        <form action="edit.php?type=<?php echo htmlspecialchars($type); ?>&id=<?php echo htmlspecialchars($id); ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <?php if ($type == 'experience'): ?>
                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" name="job_title" class="form-control" value="<?php echo htmlspecialchars($data['job_title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" name="company_name" class="form-control" value="<?php echo htmlspecialchars($data['company_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($data['start_date']); ?>" required>
                </div>
                <div class="form-group">
                    <label>End Date (leave blank for present)</label>
                    <input type="date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($data['end_date'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="5"><?php echo htmlspecialchars($data['description']); ?></textarea>
                </div>
            <?php elseif ($type == 'education'): ?>
                <div class="form-group">
                    <label>Degree</label>
                    <input type="text" name="degree" class="form-control" value="<?php echo htmlspecialchars($data['degree']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Institution</label>
                    <input type="text" name="institution" class="form-control" value="<?php echo htmlspecialchars($data['institution']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Graduation Year</label>
                    <input type="number" name="graduation_year" class="form-control" min="1900" max="2099" step="1" value="<?php echo htmlspecialchars($data['graduation_year']); ?>" required>
                </div>
            <?php elseif ($type == 'skill'): ?>
                <div class="form-group">
                    <label>Skill Name</label>
                    <input type="text" name="skill_name" class="form-control" value="<?php echo htmlspecialchars($data['skill_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Proficiency (%)</label>
                    <input type="range" name="proficiency" class="form-control" min="1" max="100" value="<?php echo htmlspecialchars($data['proficiency']); ?>" required>
                </div>
            <?php endif; ?>

            <input type="submit" class="btn btn-primary" value="Update">
            <a href="admin.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
