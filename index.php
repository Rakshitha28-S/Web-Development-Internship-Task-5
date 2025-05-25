<?php
include 'db.php';

// Fetch site settings from the database
$stmt = $conn->prepare("SELECT site_name, site_description FROM settings WHERE id = 1");
$stmt->execute();
$stmt->bind_result($site_name, $site_description);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            color: white;
            background: radial-gradient(circle at 70% 30%, #6dd5ed 0%, #2193b0 40%, #1e3c72 100%);
            position: relative;
            overflow-x: hidden;
        }
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.18;
            z-index: 0;
        }
        .bg-shape.shape1 {
            width: 320px; height: 320px;
            background: #ffc107;
            top: -80px; left: -100px;
        }
        .bg-shape.shape2 {
            width: 200px; height: 200px;
            background: #007bff;
            bottom: 60px; right: -70px;
        }
        .bg-shape.shape3 {
            width: 120px; height: 120px;
            background: #28a745;
            top: 60%; left: 70%;
        }
        .welcome-container {
            min-height: calc(100vh - 60px);
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
            position: relative;
            z-index: 1;
        }
        .card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 1.5rem;
            padding: 2.5rem 2rem 2rem 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            max-width: 700px;
            width: 100%;
            margin: auto;
        }
        .hero-svg {
            width: 140px;
            height: 140px;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #2193b0 60%, #ffc107 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 24px rgba(33,147,176,0.18);
        }
        .hero-svg i {
            font-size: 4.5rem;
            color: #fff;
            text-shadow: 0 2px 8px #2193b0;
        }
        .card h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #1e3c72;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5em;
        }
        .card h1 .bi-camera2 {
            color: #2193b0;
        }
        .card p {
            font-size: 1.15rem;
            color: #495057;
            margin-bottom: 2rem;
        }
        .btn {
            border-radius: 30px;
            font-size: 1.1rem;
            padding: 10px 20px;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.7em;
        }
        .btn-primary {
            background: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background: #0056b3;
            transform: scale(1.07);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }
        .btn-success {
            background: #28a745;
            border: none;
        }
        .btn-success:hover {
            background: #1e7e34;
            transform: scale(1.07);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        .btn-warning {
            background: #ffc107;
            border: none;
            color: #212529;
        }
        .btn-warning:hover {
            background: #e0a800;
            transform: scale(1.07);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }
        .footer {
            height: 60px;
            background: rgba(0, 0, 0, 0.85);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1rem;
            position: fixed;
            bottom: 0;
            width: 100%;
            letter-spacing: 0.5px;
            z-index: 2;
        }
        @media (max-width: 600px) {
            .card {
                padding: 1.2rem 0.5rem;
            }
            .hero-svg {
                width: 90px;
                height: 90px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-shape shape1"></div>
    <div class="bg-shape shape2"></div>
    <div class="bg-shape shape3"></div>
    <div class="welcome-container">
        <div class="card">
            <!-- Modern camera icon in a gradient circle -->
            <div class="hero-svg mx-auto d-flex align-items-center justify-content-center">
                <i class="bi bi-camera-fill"></i>
            </div>
            <h1>
                <i class="bi bi-camera2"></i>
                <?php echo htmlspecialchars($site_name); ?>
            </h1>
            <p><?php echo htmlspecialchars($site_description); ?></p>
            <div class="d-grid gap-3 mt-4">
                <a href="login.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
                <a href="register.php" class="btn btn-success btn-lg">
                    <i class="bi bi-person-plus"></i> Register
                </a>
                <a href="admin_login.php" class="btn btn-warning btn-lg">
                    <i class="bi bi-shield-lock"></i> Admin Login
                </a>
            </div>
        </div>
    </div>
    <div class="footer">
        &copy; 2025 Blog Application. All rights reserved.
    </div>
</body>
</html>