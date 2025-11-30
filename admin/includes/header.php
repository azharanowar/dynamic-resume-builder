<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        body { font: 14px sans-serif; }
        .wrapper { max-width: 800px; margin: auto; padding: 20px; }
        .navbar {
            background-color: #333;
            overflow: hidden;
            width: 100%;
        }
        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        .navbar li {
            float: left;
        }
        .navbar li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar li a:hover {
            background-color: #111;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="admin.php">Admin</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>