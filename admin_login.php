<?php
include "db.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Check if the admin exists in the database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ? AND role = 'admin'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($admin_id, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Store admin details in session
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['user_id'] = $admin_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'admin';
            $_SESSION['last_activity'] = time();

            // Redirect to admin dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No admin found with that username.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html, body { height: 100%; }
        body {
            min-height: 100vh;
            min-width: 100vw;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            color: white;
            background: radial-gradient(circle at 70% 30%, #6dd5ed 0%, #2193b0 40%, #1e3c72 100%);
            position: relative;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.18;
            z-index: 0;
        }
        .bg-shape.shape1 { width: 220px; height: 220px; background: #ffc107; top: -60px; left: -80px; }
        .bg-shape.shape2 { width: 140px; height: 140px; background: #007bff; bottom: 40px; right: -50px; }
        .bg-shape.shape3 { width: 80px; height: 80px; background: #28a745; top: 65%; left: 75%; }
        .main-wrapper {
            min-height: 100vh;
            min-width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            animation: fadeIn 1s ease-in-out;
            z-index: 2;
        }
        .card h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #1e3c72;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-control {
            border-radius: 20px;
            padding: 8px 12px;
            font-size: 0.95rem;
        }
        .btn-success {
            background: #28a745;
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-success:hover {
            background: #218838;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4);
        }
        .alert {
            border-radius: 20px;
            animation: fadeIn 0.8s ease-in-out;
        }
        .home-btn {
            position: fixed;
            top: 25px;
            left: 25px;
            background: rgba(255, 255, 255, 0.8);
            color: #1e3c72;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            z-index: 10;
        }
        .home-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.5);
        }
        .footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 8px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 0.8rem;
            z-index: 2;
        }
        @media (max-width: 600px) {
            .card {
                padding: 1.2rem 0.5rem;
                max-width: 95vw;
            }
            .home-btn {
                top: 10px;
                left: 10px;
                padding: 6px 10px;
                font-size: 0.8rem;
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px);}
            to { opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <div class="bg-shape shape1"></div>
    <div class="bg-shape shape2"></div>
    <div class="bg-shape shape3"></div>
    <!-- Home Button -->
    <a href="index.php" class="btn btn-secondary home-btn"><i class="bi bi-house-door"></i> Home</a>
    <div class="main-wrapper">
        <div class="card">
            <h2><i class="bi bi-shield-lock"></i> Admin Login</h2>
            <form method="post" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required placeholder="Enter your username">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-success" type="submit"><i class="bi bi-box-arrow-in-right"></i> Login</button>
                </div>
            </form>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger mt-3 text-center"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Footer -->
    <div class="footer">
        &copy; 2025 Blog Application. All rights reserved.
    </div>
</body>
</html>