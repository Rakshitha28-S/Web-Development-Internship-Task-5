<?php
include "db.php";
session_start();

// Initialize variables for error messages
$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Store user details in session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Redirect based on role
            if ($role === 'admin') {
                $error_message = "Access denied. You do not have the necessary permissions to view this page.";
            } elseif ($role === 'editor') {
                header("Location: editor_dashboard.php");
                exit();
            } elseif ($role === 'user') {
                header("Location: view_posts.php");
                exit();
            }
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with that username.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at 70% 30%, #6dd5ed 0%, #2193b0 40%, #1e3c72 100%);
            color: white;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
            min-height: 100vh;
        }
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.18;
            z-index: 0;
        }
        .bg-shape.shape1 {
            width: 220px; height: 220px;
            background: #ffc107;
            top: -60px; left: -80px;
        }
        .bg-shape.shape2 {
            width: 140px; height: 140px;
            background: #007bff;
            bottom: 40px; right: -50px;
        }
        .bg-shape.shape3 {
            width: 80px; height: 80px;
            background: #28a745;
            top: 65%; left: 75%;
        }
        .home-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.8);
            color: #1e3c72;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 1rem;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            z-index: 2;
        }
        .home-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.5);
        }
        .container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 2s ease-in-out;
            position: relative;
            z-index: 1;
        }
        .card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 1.5rem;
            padding: 2.5rem 2rem 2rem 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            max-width: 400px;
            width: 100%;
            animation: slideUp 1.5s ease-in-out;
        }
        .card h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #1e3c72;
            animation: zoomIn 1.5s ease-in-out;
        }
        .btn {
            border-radius: 30px;
            font-size: 1.1rem;
            padding: 10px 20px;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.7em;
        }
        .btn-success {
            background: #28a745;
            border: none;
        }
        .btn-success:hover {
            background: #1e7e34;
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.5);
        }
        .btn-outline-primary {
            color: #007bff;
            border: 2px solid #007bff;
        }
        .btn-outline-primary:hover {
            background: #007bff;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.5);
        }
        .alert {
            animation: fadeIn 1s ease-in-out;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #ccc;
            position: fixed;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px;
            z-index: 2;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes zoomIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="bg-shape shape1"></div>
    <div class="bg-shape shape2"></div>
    <div class="bg-shape shape3"></div>
    <!-- Home Button -->
    <a href="index.php" class="home-btn"><i class="bi bi-house-door"></i> Home</a>

    <div class="container">
        <div class="card">
            <h2 class="text-center mb-4"><i class="bi bi-person-circle"></i> Login</h2>
            <form method="post">
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
                    <a href="register.php" class="btn btn-outline-primary text-center"><i class="bi bi-person-plus"></i> Don't have an account? Register</a>
                </div>
            </form>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger mt-3 text-center"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="footer text-center">
        &copy; 2025 Blog Application. All rights reserved.
    </div>
</body>
</html>