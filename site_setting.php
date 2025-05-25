<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}

include 'db.php';

// Initialize variables for messages
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = trim($_POST['site_name']);
    $site_description = trim($_POST['site_description']);

    // Validate input
    if (empty($site_name) || empty($site_description)) {
        $error_message = "Both fields are required.";
    } else {
        // Update site settings in the database
        $stmt = $conn->prepare("UPDATE settings SET site_name = ?, site_description = ? WHERE id = 1");
        $stmt->bind_param("ss", $site_name, $site_description);

        if ($stmt->execute()) {
            $success_message = "Settings updated successfully!";
        } else {
            $error_message = "Failed to update settings. Please try again.";
        }
        $stmt->close();
    }
}

// Fetch current settings
$stmt = $conn->prepare("SELECT site_name, site_description FROM settings WHERE id = 1");
$stmt->execute();
$stmt->bind_result($site_name, $site_description);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Site Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
            overflow-x: hidden;
        }
        .settings-title {
            font-size: 2.4rem;
            font-weight: bold;
            color: #0077b6;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #b3c6e0;
        }
        .settings-subtitle {
            color: #495057;
            font-size: 1.15rem;
            margin-bottom: 2rem;
        }
        .card {
            border-radius: 2rem;
            box-shadow: 0 8px 32px rgba(33,147,176,0.18);
            border: none;
            background: rgba(255,255,255,0.98);
            max-width: 900px;
            margin: 0 auto;
            padding: 3.5rem 3rem 2.5rem 3rem;
            position: relative;
            z-index: 2;
        }
        .form-label {
            font-weight: 600;
            color: #023e8a;
        }
        .form-control, textarea.form-control {
            border-radius: 1.2rem;
            font-size: 1.18rem;
            background: #f7fbff;
            border: 1.5px solid #bde0fe;
            transition: border-color 0.2s;
        }
        .form-control:focus, textarea.form-control:focus {
            border-color: #0077b6;
            box-shadow: 0 0 0 0.2rem rgba(33,147,176,0.10);
        }
        .btn-success {
            border-radius: 30px;
            padding: 12px 38px;
            font-size: 1.18rem;
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            border: none;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(67,233,123,0.08);
            transition: background 0.2s, transform 0.2s;
        }
        .btn-success:hover {
            background: linear-gradient(90deg, #38f9d7 0%, #43e97b 100%);
            color: #fff;
            transform: scale(1.04);
        }
        .btn-secondary {
            border-radius: 30px;
            padding: 12px 32px;
            font-size: 1.13rem;
            font-weight: 500;
        }
        .alert {
            border-radius: 1.2rem;
            font-size: 1.08rem;
        }
        .icon-bg {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(33,147,176,0.13);
            z-index: 3;
        }
        .icon-bg i {
            font-size: 2.2rem;
            color: #fff;
        }
        @media (max-width: 900px) {
            .card {
                max-width: 98vw;
                padding: 1.5rem 0.5rem 1.5rem 0.5rem;
            }
        }
        @media (max-width: 600px) {
            .settings-title {
                font-size: 1.3rem;
            }
            .settings-subtitle {
                font-size: 1rem;
            }
            .btn, .btn-danger {
                font-size: 0.97rem;
                padding: 7px 14px;
            }
            .icon-bg {
                width: 50px;
                height: 50px;
                top: -25px;
            }
            .icon-bg i {
                font-size: 1.3rem;
            }
        }
        /* Decorative background shapes */
        .bg-shape1, .bg-shape2 {
            position: absolute;
            border-radius: 50%;
            opacity: 0.13;
            z-index: 0;
        }
        .bg-shape1 {
            width: 220px; height: 220px;
            background: #48c6ef;
            top: -60px; left: -80px;
        }
        .bg-shape2 {
            width: 140px; height: 140px;
            background: #43e97b;
            bottom: 40px; right: -50px;
        }
    </style>
</head>
<body>
    <div class="bg-shape1"></div>
    <div class="bg-shape2"></div>
    <div class="container py-5 position-relative" style="z-index:2;">
        <div class="text-center mb-2">
            <div class="settings-title"><i class="bi bi-gear"></i> Site Settings</div>
            <div class="settings-subtitle">Update your application settings below.</div>
        </div>
        <div class="card mt-4 shadow position-relative">
            <div class="icon-bg"><i class="bi bi-gear"></i></div>
            <!-- Display success or error messages -->
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success text-center mt-4" id="successMsg">
                    <i class="bi bi-check-circle-fill"></i> <?php echo htmlspecialchars($success_message); ?>
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('successMsg').style.display = 'none';
                    }, 3000);
                </script>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger text-center mt-4"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <!-- Settings Form -->
            <form method="post" autocomplete="off" class="mt-4">
                <div class="mb-3">
                    <label class="form-label">Site Name</label>
                    <input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars($site_name); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Site Description</label>
                    <textarea name="site_description" class="form-control" rows="6" required><?php echo htmlspecialchars($site_description); ?></textarea>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save Settings</button>
                    <a href="admin_dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>