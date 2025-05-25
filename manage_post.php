<?php
session_start();
include "db.php";

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}

// Pagination setup
$limit = 5; // Show 5 posts per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = '';
$where = '';
$params = [];
$types = '';
if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $search = trim($_GET['search']);
    $where = "WHERE posts.title LIKE ?";
    $params[] = "%$search%";
    $types .= 's';
}

// Count total posts for pagination
$count_query = "SELECT COUNT(*) FROM posts JOIN users ON posts.user_id = users.id $where";
$count_stmt = $conn->prepare($count_query);
if ($where) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$count_stmt->bind_result($total_posts);
$count_stmt->fetch();
$count_stmt->close();
$total_pages = ceil($total_posts / $limit);

// Handle delete post
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_posts.php?msg=deleted");
    exit();
}

// Fetch posts with pagination
$query = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.username 
          FROM posts 
          JOIN users ON posts.user_id = users.id 
          $where 
          ORDER BY posts.created_at DESC 
          LIMIT ? OFFSET ?";
$params2 = $params;
$types2 = $types . 'ii';
$params2[] = $limit;
$params2[] = $offset;

$stmt = $conn->prepare($query);
if ($where) {
    $stmt->bind_param($types2, ...$params2);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .main-title {
            font-size: 2.2rem;
            font-weight: bold;
            color: #2193b0;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #b3c6e0;
        }
        .subtitle {
            color: #495057;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
        .table {
            background: #fff;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(33,147,176,0.10);
        }
        th {
            background: #2193b0;
            color: #fff;
            font-weight: 600;
            text-align: center;
        }
        td {
            vertical-align: middle !important;
            text-align: center;
        }
        .btn-danger {
            border-radius: 20px;
            font-size: 0.95rem;
            padding: 5px 16px;
            background: linear-gradient(90deg, #dc3545 60%, #f8d7da 100%);
            color: #fff;
            border: none;
        }
        .btn-danger:hover {
            background: linear-gradient(90deg, #b52a37 60%, #dc3545 100%);
            color: #fff;
        }
        .btn-secondary {
            border-radius: 20px;
            padding: 8px 24px;
            font-size: 1.1rem;
            margin-top: 20px;
        }
        .msg {
            margin-top: 20px;
            border-radius: 1rem;
        }
        .post-content-full {
            max-width: 400px;
            white-space: pre-wrap;
            word-break: break-word;
            font-size: 0.97rem;
            color: #555;
            background: #f7fbff;
            border-radius: 0.5rem;
            padding: 6px 10px;
        }
        .search-bar {
            max-width: 400px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(33,147,176,0.08);
            border-radius: 30px;
            background: #fff;
            padding: 0.5rem 1rem;
        }
        .search-bar input {
            border: none;
            background: transparent;
            font-size: 1.1rem;
        }
        .search-bar input:focus {
            box-shadow: none;
            outline: none;
        }
        .search-bar .btn {
            border-radius: 30px;
            padding: 0.5rem 1.2rem;
        }
        .table-responsive {
            border-radius: 1rem;
            overflow: hidden;
        }
        .pagination {
            justify-content: center;
            margin-top: 30px;
        }
        .page-link {
            border-radius: 50px !important;
            margin: 0 2px;
            color: #2193b0;
            font-weight: 500;
        }
        .page-item.active .page-link {
            background: linear-gradient(90deg, #2193b0 60%, #6dd5ed 100%);
            color: #fff;
            border: none;
        }
        @media (max-width: 600px) {
            .main-title {
                font-size: 1.2rem;
            }
            .subtitle {
                font-size: 0.95rem;
            }
            .btn-secondary {
                font-size: 0.95rem;
                padding: 6px 14px;
            }
            .post-content-full {
                max-width: 90vw;
                font-size: 0.93rem;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="text-center">
            <div class="main-title"><i class="bi bi-file-earmark-post"></i> Manage Posts</div>
            <div class="subtitle">View and delete any post created by users.</div>
        </div>
        <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap">
            <a href="admin_dashboard.php" class="btn btn-secondary mb-2"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
            <form class="d-flex search-bar" method="get" action="manage_posts.php">
                <input class="form-control me-2" type="search" name="search" placeholder="Search by title..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
            </form>
        </div>
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-success msg text-center"><i class="bi bi-check-circle-fill"></i> Post deleted successfully.</div>
            <script>
                setTimeout(function() {
                    document.querySelector('.alert-success.msg').style.display = 'none';
                }, 3000);
            </script>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Author</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): $i = $offset + 1; ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($row['title']); ?></td>
                                <td class="post-content-full"><?= nl2br(htmlspecialchars($row['content'])); ?></td>
                                <td><?= htmlspecialchars($row['username']); ?></td>
                                <td><?= htmlspecialchars($row['created_at']); ?></td>
                                <td>
                                    <a href="manage_posts.php?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?');">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No posts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav>
            <ul class="pagination">
                <!-- First page arrow -->
                <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>" title="First"><i class="bi bi-chevron-double-left"></i></a>
                </li>
                <!-- Previous page arrow -->
                <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" title="Previous"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php
                // Show up to 5 page numbers, centered on current page
                $start = max(1, $page - 2);
                $end = min($total_pages, $page + 2);
                if ($end - $start < 4) {
                    if ($start == 1) $end = min($total_pages, $start + 4);
                    if ($end == $total_pages) $start = max(1, $end - 4);
                }
                for ($p = $start; $p <= $end; $p++): ?>
                    <li class="page-item<?= $p == $page ? ' active' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $p])) ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>
                <!-- Next page arrow -->
                <li class="page-item<?= $page >= $total_pages ? ' disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" title="Next"><i class="bi bi-chevron-right"></i></a>
                </li>
                <!-- Last page arrow -->
                <li class="page-item<?= $page >= $total_pages ? ' disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>" title="Last"><i class="bi bi-chevron-double-right"></i></a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</body>
</html>