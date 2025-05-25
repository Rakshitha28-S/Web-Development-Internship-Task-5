<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Access+denied");
    exit;
}

$post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$post_id) {
    header("Location: editor_dashboard.php?error=Invalid+post+ID");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if post exists and belongs to user
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    error_log("Unauthorized access attempt by user_id: $user_id for post_id: $post_id");
    header("Location: editor_dashboard.php?error=You+are+not+authorized+to+edit+this+post");
    exit;
}

$post = $result->fetch_assoc();

// Handle form submission
$update_success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $update_stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
    $update_stmt->bind_param("ssii", $title, $content, $post_id, $user_id);

    if ($update_stmt->execute()) {
        $update_success = true;
    } else {
        echo "<div class='alert alert-danger text-center'>Update failed.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .container {
            max-width: 650px;
            margin-top: 60px;
        }
        .card {
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(33,147,176,0.13);
            border: none;
            background: #fff;
        }
        .form-label {
            font-weight: 600;
            color: #1e3c72;
        }
        .form-control, textarea.form-control {
            border-radius: 1rem;
            font-size: 1.08rem;
            background: #f7fbff;
        }
        .form-control:focus, textarea.form-control:focus {
            border-color: #2193b0;
            box-shadow: 0 0 0 0.2rem rgba(33,147,176,0.08);
        }
        .btn-success {
            border-radius: 30px;
            padding: 8px 28px;
            font-size: 1.1rem;
            background: linear-gradient(90deg, #28a745 60%, #6dd5ed 100%);
            border: none;
        }
        .btn-success:hover {
            background: linear-gradient(90deg, #2193b0 60%, #28a745 100%);
            color: #fff;
            transform: scale(1.05);
        }
        .btn-secondary {
            border-radius: 30px;
            padding: 8px 24px;
            font-size: 1.1rem;
        }
        .main-title {
            font-size: 2rem;
            font-weight: bold;
            color: #2193b0;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #b3c6e0;
            display: flex;
            align-items: center;
            gap: 0.5em;
        }
        .alert {
            border-radius: 1rem;
        }
        @media (max-width: 600px) {
            .container {
                margin-top: 20px;
                padding: 0 5px;
            }
            .main-title {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card shadow p-4">
        <div class="main-title mb-4">
            <i class="bi bi-pencil-square"></i> Edit Post
        </div>

        <?php if ($update_success): ?>
            <div class="alert alert-success text-center">
                <i class="bi bi-check-circle-fill"></i> Post updated successfully!
                <br>
                <a href="editor_dashboard.php" class="btn btn-primary mt-3"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
            </div>
            <script>
                setTimeout(() => window.location.href = 'editor_dashboard.php', 2000);
            </script>
        <?php else: ?>
            <form method="post" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title']); ?>" required maxlength="255" placeholder="Enter title">
                </div>
                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-control" rows="7" required placeholder="Enter content"><?= htmlspecialchars($post['content']); ?></textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="bi bi-pencil-square"></i> Update</button>
                    <a href="editor_dashboard.php" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
