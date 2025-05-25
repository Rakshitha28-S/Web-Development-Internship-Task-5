<?php
include "db.php";
session_start();

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $role = trim($_POST["role"]);

    // Validate input
    if (strlen($username) < 3) {
        $error_message = "Username must be at least 3 characters.";
    } elseif (strlen($password) < 5) {
        $error_message = "Password must be at least 5 characters.";
    } elseif (!in_array($role, ['user', 'editor'])) {
        $error_message = "Invalid role selected.";
    } else {
        // Check if the username already exists
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $error_message = "Username already exists. Please choose a different username.";
        } else {
            // Insert the new user into the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $role);

            if ($stmt->execute()) {
                $success_message = "Registration successful! Redirecting to login...";
                echo "<script>setTimeout(function() { window.location.href = 'login.php'; }, 2000);</script>";
            } else {
                $error_message = "Error: Could not register. Please try again later.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.9rem;
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
            animation: fadeIn 1.5s ease-in-out;
            position: relative;
            z-index: 1;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 450px;
            width: 100%;
            animation: slideUp 1.2s ease-in-out;
        }
        .card h2 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #1e3c72;
            animation: zoomIn 1.2s ease-in-out;
        }
        .btn {
            border-radius: 25px;
            font-size: 1rem;
            padding: 8px 16px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-success {
            background: #28a745;
            border: none;
        }
        .btn-success:hover {
            background: #1e7e34;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        }
        .btn-outline-primary {
            color: #007bff;
            border: 2px solid #007bff;
        }
        .btn-outline-primary:hover {
            background: #007bff;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        }
        .alert {
            animation: fadeIn 1s ease-in-out;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px;
            font-size: 0.85rem;
            text-align: center;
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
            from { transform: scale(0.9); opacity: 0; }
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
            <h2 class="text-center mb-4"><i class="bi bi-person-plus"></i> Register</h2>
            <form method="post" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required minlength="3" placeholder="Enter new username">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="5" placeholder="Enter new password">
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="user">User</option>
                        <option value="editor">Editor</option>
                    </select>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-success" type="submit"><i class="bi bi-person-plus"></i> Sign Up</button>
                    <a href="login.php" class="btn btn-outline-primary text-center"><i class="bi bi-box-arrow-in-right"></i> Already have an account? Login</a>
                </div>
            </form>
            <?php if ($success_message): ?>
                <div class="alert alert-success mt-3 text-center"><?php echo htmlspecialchars($success_message); ?></div>
            <?php elseif ($error_message): ?>
                <div class="alert alert-danger mt-3 text-center"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="footer">
        &copy; 2025 Blog Application. All rights reserved.
    </div>
</body>
</html>