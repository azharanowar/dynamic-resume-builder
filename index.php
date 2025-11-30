<?php
require_once 'admin/includes/db_connect.php'; 

// Fetch profile data
$profile_result = $mysqli->query("SELECT * FROM profile WHERE id = 1");
$profile = $profile_result ? $profile_result->fetch_assoc() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azhar Anowar - Web Developer Resume</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php if (!$profile): ?>
    <div class="resume-container" style="text-align: center; padding: 50px; display: block;">
        <h1>Welcome to Dynamic Resume Builder!</h1>
        <p>Your resume is not yet configured.</p>
        <p>Please log in to the <a href="admin/login.php">admin panel</a> to add your information.</p>
    </div>
    <?php else: ?>
    <div class="resume-container">
        
        <aside class="sidebar">
            <div class="profile-section">
                <img src="<?php echo 'admin/' . htmlspecialchars($profile['profile_image'] ?? 'assets/images/default-profile.png'); ?>" alt="Profile Picture" class="profile-img">
                <h1 class="name"><?php echo htmlspecialchars($profile['full_name'] ?? 'Your Name'); ?></h1>
                <p class="role"><?php echo htmlspecialchars($profile['role'] ?? 'Your Role'); ?></p>
            </div>

            <div class="divider"></div>

            <div class="contact-list">
                <?php if (!empty($profile['github_url'])): ?>
                <div class="contact-item">
                    <div class="icon-box"><i class="fas fa-link"></i></div>
                    <div class="contact-text">
                        <div>Github</div>
                        <div><a href="<?php echo htmlspecialchars($profile['github_url']); ?>" target="_blank"><?php echo preg_replace('#^https?://#', '', htmlspecialchars($profile['github_url'])); ?></a></div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($profile['email'])): ?>
                <div class="contact-item">
                    <div class="icon-box"><i class="fas fa-envelope"></i></div>
                    <div class="contact-text">
                        <div>Email</div>
                        <div><a href="mailto:<?php echo htmlspecialchars($profile['email']); ?>"><?php echo htmlspecialchars($profile['email']); ?></a></div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($profile['phone'])): ?>
                <div class="contact-item">
                    <div class="icon-box"><i class="fas fa-phone-alt"></i></div>
                    <div class="contact-text">
                        <div>Phone</div>
                        <div><?php echo htmlspecialchars($profile['phone']); ?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="divider"></div>

            <div class="social-list">
                <!-- Social links can be added here similarly if needed -->
            </div>
        </aside>


        <main class="main-content">
            
            <section class="section">
                <h2 class="section-title">About</h2>
                <p class="content-text">
                    <?php echo nl2br(htmlspecialchars($profile['about_me'] ?? 'About me section.')); ?>
                </p>
            </section>

            <section class="section">
                <h2 class="section-title">Experience</h2>

                <?php
                $sql = "SELECT *, DATE_FORMAT(start_date, '%b %Y') as formatted_start, DATE_FORMAT(end_date, '%b %Y') as formatted_end FROM experience ORDER BY start_date DESC";
                if($result = $mysqli->query($sql)){
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $date_range = $row['formatted_start'] . ' - ';
                            $date_range .= $row['end_date'] ? $row['formatted_end'] : 'Present';
                ?>
                            <div class="job-block">
                                <div class="job-header">
                                    <span class="job-title"><?php echo htmlspecialchars($row['job_title']); ?> <span class="company-divider">|</span> <?php echo htmlspecialchars($row['company_name']); ?></span>
                                    <div class="date-location"><?php echo $date_range; ?></div>
                                </div>
                                <p class="content-text">
                                    <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                                </p>
                            </div>
                <?php
                        }
                        $result->free();
                    } else {
                        echo '<p class="content-text">No professional experience has been added yet.</p>';
                    }
                } else {
                    echo '<p class="content-text">Error fetching experience data.</p>';
                }
                ?>

            </section>

            <section class="section">
                <h2 class="section-title">Education</h2>
                <?php
                $sql = "SELECT *, DATE_FORMAT(start_date, '%b %Y') as formatted_start, DATE_FORMAT(graduation_date, '%b %Y') as formatted_grad FROM education ORDER BY start_date DESC";
                if($result = $mysqli->query($sql)){
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $date_range = $row['formatted_start'] . ' - ';
                            $date_range .= $row['graduation_date'] ? $row['formatted_grad'] : 'Present';
                ?>
                            <div class="job-block">
                                <div class="job-header">
                                    <span class="job-title"><?php echo htmlspecialchars($row['degree']); ?> <span class="company-divider">|</span> <?php echo htmlspecialchars($row['institution']); ?></span>
                                    <div class="date-location"><?php echo $date_range; ?></div>
                                </div>
                                <?php if(!empty($row['description'])): ?>
                                <p class="content-text">
                                    <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                                </p>
                                <?php endif; ?>
                            </div>
                <?php
                        }
                        $result->free();
                    } else {
                        echo '<p class="content-text">No education history has been added yet.</p>';
                    }
                } else {
                    echo '<p class="content-text">Error fetching education data.</p>';
                }
                ?>
            </section>

            <section class="section" style="margin-bottom: 0;">
                <h2 class="section-title">Skills</h2>
                <div class="skills-list">
                <?php
                $sql = "SELECT name, proficiency FROM skills ORDER BY proficiency DESC";
                if($result = $mysqli->query($sql)){
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                ?>
                        <div class="skill-item">
                            <span class="skill-name"><?php echo htmlspecialchars($row['name']); ?></span>
                            <div class="skill-bar">
                                <div class="skill-level" style="width: <?php echo $row['proficiency']; ?>%;"></div>
                            </div>
                        </div>
                <?php
                        }
                        $result->free();
                    } else {
                        echo '<p class="content-text">No skills have been added yet.</p>';
                    }
                } else {
                    echo '<p class="content-text">Error fetching skills data.</p>';
                }
                ?>
                </div>
            </section>
            

        </main>
    </div>
    <?php endif; ?>
  
<?php
    // Close connection
    $mysqli->close();
?>

</body>
</html>

<div class="admin-login-details">
    <h3>Admin credentials for customization: <a href="admin/" target="_blank">Admin Panel</a></h3>
    <p>Email: azharanowar@gmail.com, Password: 123</p>
</div>