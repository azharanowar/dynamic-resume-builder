<?php
session_start();
require_once 'includes/db_connect.php';

// If user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

$message = '';
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $github_url = $_POST['github_url'];
    $about_me = $_POST['about_me'];
    $profile_image_path = $_POST['existing_profile_image']; // Keep existing image by default

    // --- Profile Image Upload Logic ---
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['profile_image'];
        $upload_dir = 'uploads/';

        // 1. Validation: Size Check (max 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = "File is too large. Maximum size is 2MB.";
        } else {
            // 2. Validation: Type Check (MIME type)
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $finfo->file($file['tmp_name']);
            $allowed_types = ['image/jpeg', 'image/png'];

            if (!in_array($mime_type, $allowed_types)) {
                $errors[] = "Invalid file type. Only JPG and PNG are allowed.";
            } else {
                // 3. Secure Naming & Path
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $unique_name = 'profile_' . uniqid() . '.' . $extension;
                $target_path = $upload_dir . $unique_name;

                // 4. Image Processing (GD Library) & Move
                $image_resource = null;
                if ($mime_type == 'image/jpeg') {
                    $image_resource = imagecreatefromjpeg($file['tmp_name']);
                } elseif ($mime_type == 'image/png') {
                    $image_resource = imagecreatefrompng($file['tmp_name']);
                }

                if ($image_resource) {
                    // Create a 150x150 thumbnail
                    $thumbnail = imagescale($image_resource, 150, 150);
                    if ($thumbnail) {
                        // Save the thumbnail
                        if ($mime_type == 'image/jpeg') {
                            imagejpeg($thumbnail, $target_path, 90);
                        } elseif ($mime_type == 'image/png') {
                            imagepng($thumbnail, $target_path, 9);
                        }
                        imagedestroy($thumbnail);
                        $profile_image_path = 'uploads/' . $unique_name; // Path to save in DB
                    } else {
                        $errors[] = "Failed to resize the image.";
                    }
                    imagedestroy($image_resource);
                } else {
                    $errors[] = "Failed to process the image file.";
                }
            }
        }
    }

    // If no validation errors, update the database
    if (empty($errors)) {
        // Use prepared statements to prevent SQL injection
        $sql = "UPDATE profile SET
                    full_name = ?,
                    role = ?,
                    email = ?,
                    phone = ?,
                    github_url = ?,
                    about_me = ?,
                    profile_image = ?
                WHERE id = 1";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssss", $full_name, $role, $email, $phone, $github_url, $about_me, $profile_image_path);
        if ($stmt->execute()) {
            $message = "Profile updated successfully!";
        } else {
            $errors[] = "Database update failed: " . $stmt->error;
        }
    }
}

// Fetch existing profile data to pre-fill the form
$profile_result = $mysqli->query("SELECT * FROM profile WHERE id = 1");
$profile = $profile_result ? $profile_result->fetch_assoc() : null;

if (!$profile) {
    // If no profile exists, create a default one to avoid errors.
    $mysqli->query("INSERT INTO profile (id) VALUES (1)");
    $profile_result = $mysqli->query("SELECT * FROM profile WHERE id = 1");
    $profile = $profile_result->fetch_assoc();
}
?>

<?php include 'includes/header.php'; ?>
    <div class="admin-wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Edit Your Profile</h1>
            <a href="admin.php" class="btn">Go to Admin Panel →</a>
        </div>
        <?php if ($message): ?>
            <div class="alert success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="content-block">
            <form action="profile.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo htmlspecialchars($profile['full_name'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="profile_image">Profile Picture (JPG, PNG, and JPEG max 2MB)</label>
                    <?php if (!empty($profile['profile_image'])): ?>
                        <img src="<?php echo htmlspecialchars($profile['profile_image']); ?>" alt="Current Profile Picture" title="Current Profile Image" style="max-width: 100px; display: block; margin-bottom: 10px;">
                    <?php endif; ?>
                    <input type="file" id="profile_image" name="profile_image" class="form-control">
                    <input type="hidden" name="existing_profile_image" value="<?php echo htmlspecialchars($profile['profile_image'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="role">Role / Job Title</label>
                    <input type="text" id="role" name="role" class="form-control" value="<?php echo htmlspecialchars($profile['role'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="about_me">About Me</label>
                    <textarea id="about_me" name="about_me" class="form-control" rows="5"><?php echo htmlspecialchars($profile['about_me'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="github_url">GitHub URL</label>
                    <input type="url" id="github_url" name="github_url" class="form-control" value="<?php echo htmlspecialchars($profile['github_url'] ?? ''); ?>">
                </div>

                <button type="submit" class="btn">Save Changes</button>
            </form>
        </div>
    </div>

</body>
</html>