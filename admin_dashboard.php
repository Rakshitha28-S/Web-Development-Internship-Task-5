<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .dashboard-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2193b0;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #b3c6e0;
        }
        .dashboard-subtitle {
            color: #495057;
            font-size: 1.15rem;
            margin-bottom: 2.5rem;
        }
        .card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(33,147,176,0.13);
            background: #fff;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-bottom: 2rem;
        }
        .card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 12px 40px rgba(33,147,176,0.18);
        }
        .card-title {
            font-size: 1.5rem;
            color: #1e3c72;
            font-weight: 600;
        }
        .dashboard-icon {
            font-size: 2.7rem;
            color: #2193b0;
            margin-bottom: 0.7rem;
        }
        .btn {
            border-radius: 30px;
            font-size: 1.1rem;
            padding: 8px 24px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-primary {
            background: linear-gradient(90deg, #2193b0 60%, #6dd5ed 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #007bff 60%, #2193b0 100%);
            transform: scale(1.07);
            box-shadow: 0 5px 15px rgba(33,147,176,0.18);
        }
        .btn-secondary {
            background: linear-gradient(90deg, #6c757d 60%, #adb5bd 100%);
            border: none;
        }
        .btn-secondary:hover {
            background: linear-gradient(90deg, #495057 60%, #6c757d 100%);
            transform: scale(1.07);
            box-shadow: 0 5px 15px rgba(108,117,125,0.18);
        }
        .btn-warning {
            background: linear-gradient(90deg, #ffc107 60%, #ffe082 100%);
            color: #212529;
            border: none;
        }
        .btn-warning:hover {
            background: linear-gradient(90deg, #e0a800 60%, #ffc107 100%);
            color: #212529;
            transform: scale(1.07);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.18);
        }
        .btn-danger {
            border-radius: 30px;
            padding: 8px 24px;
            font-size: 1.1rem;
        }
        .logout-section {
            margin-top: 60px;
        }
        @media (max-width: 900px) {
            .dashboard-title {
                font-size: 1.5rem;
            }
            .card-title {
                font-size: 1.1rem;
            }
            .dashboard-icon {
                font-size: 2rem;
            }
        }
        @media (max-width: 600px) {
            .dashboard-title {
                font-size: 1.1rem;
            }
            .dashboard-subtitle {
                font-size: 0.95rem;
            }
            .btn, .btn-danger {
                font-size: 0.95rem;
                padding: 6px 14px;
            }
        }
    </style>
</head>
<body class="container py-5">
    <div class="text-center mb-4">
        <div class="dashboard-title"><i class="bi bi-shield-lock"></i> Admin Dashboard</div>
        <div class="dashboard-subtitle">Welcome, Admin! Here you can manage the application and its users.</div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4 mb-4">
            <div class="card p-4 h-100">
                <div class="dashboard-icon text-center"><i class="bi bi-people"></i></div>
                <h5 class="card-title text-center">Manage Users</h5>
                <p class="text-center">View, edit, or delete user accounts.</p>
                <div class="text-center">
                    <a href="manage_users.php" class="btn btn-secondary"><i class="bi bi-people"></i> Manage Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card p-4 h-100">
                <div class="dashboard-icon text-center"><i class="bi bi-file-earmark-post"></i></div>
                <h5 class="card-title text-center">Manage Posts</h5>
                <p class="text-center">View and delete any post created by users.</p>
                <div class="text-center">
                    <a href="manage_posts.php" class="btn btn-primary"><i class="bi bi-file-earmark-post"></i> Manage Posts</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card p-4 h-100">
                <div class="dashboard-icon text-center"><i class="bi bi-gear"></i></div>
                <h5 class="card-title text-center">Site Settings</h5>
                <p class="text-center">Configure application settings.</p>
                <div class="text-center">
                    <a href="site_settings.php" class="btn btn-warning"><i class="bi bi-gear"></i> Settings</a>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center logout-section">
        <a href="logout.php" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</body>
</html>