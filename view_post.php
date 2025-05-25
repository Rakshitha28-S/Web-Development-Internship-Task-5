<?php
include "auth.php"; // Ensure the user is authenticated
include "db.php"; // Include database connection

// Allow only admins and editors to access this page
checkRole(['admin', 'editor', 'user']);

// Handle search and pagination
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Posts per page
$offset = ($page - 1) * $limit;

$whereClause = "";
$params = [];
$types = "";

if ($search !== '') {
    $whereClause = "WHERE posts.title LIKE ? OR posts.content LIKE ?";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types = "ss";
}

// Count total posts for pagination
$countSql = "SELECT COUNT(*) FROM posts JOIN users ON posts.user_id = users.id " . $whereClause;
$stmt = $conn->prepare($countSql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$stmt->bind_result($totalPosts);
$stmt->fetch();
$stmt->close();

$totalPages = ceil($totalPosts / $limit);

// Fetch posts with limit and offset
$sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id " . 
       ($whereClause ? $whereClause . " " : "") . "ORDER BY created_at DESC LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

function refValues($arr) {
    $refs = [];
    foreach ($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}

if (!empty($params)) {
    $types .= "ii";
    $params[] = $limit;
    $params[] = $offset;
    $bindParams = array_merge([$types], $params);
    call_user_func_array([$stmt, 'bind_param'], refValues($bindParams));
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
        }
        .dashboard-btn, .logout-btn {
            position: fixed;
            top: 24px;
            z-index: 10;
        }
        .dashboard-btn {
            left: 24px;
        }
        .logout-btn {
            right: 24px;
        }
        .main-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2193b0;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #b3c6e0;
        }
        .search-bar {
            max-width: 500px;
            margin: 0 auto 2rem auto;
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
        .post-card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(33,147,176,0.10);
            margin-bottom: 2rem;
            background: #fff;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .post-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 8px 32px rgba(33,147,176,0.18);
        }
        .post-title {
            font-size: 1.6rem;
            font-weight: 600;
            color: #1e3c72;
            margin-bottom: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.5em;
        }
        .post-meta {
            font-size: 0.98rem;
            color: #888;
            margin-bottom: 0.5rem;
        }
        .post-content {
            font-size: 1.08rem;
            color: #495057;
            margin-bottom: 0.5rem;
            white-space: pre-line;
        }
        .no-posts {
            text-align: center;
            color: #888;
            font-size: 1.2rem;
            margin-top: 2rem;
        }
        .pagination {
            margin-top: 30px;
        }
        .page-link {
            color: #007bff;
            border-radius: 50% !important;
        }
        .page-item.active .page-link {
            background-color: #2193b0;
            border-color: #2193b0;
            color: white;
        }
        @media (max-width: 600px) {
            .main-title {
                font-size: 1.5rem;
            }
            .dashboard-btn, .logout-btn {
                top: 10px;
                left: 10px;
                right: 10px;
                font-size: 0.9rem;
                padding: 6px 10px;
            }
            .search-bar {
                max-width: 98vw;
            }
            .post-title {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body class="container py-4">
    <!-- Back to Dashboard Button for Editor -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'editor'): ?>
        <a href="editor_dashboard.php" class="btn btn-secondary dashboard-btn"><i class="bi bi-speedometer"></i> Editor Dashboard</a>
    <?php endif; ?>

    <!-- Logout Button -->
    <a href="logout.php" class="btn btn-danger logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>

    <h2 class="main-title text-center"><i class="bi bi-journal-richtext"></i> Blog Posts</h2>

    <form method="get" class="search-bar d-flex align-items-center mb-4 shadow-sm">
        <input type="text" name="search" class="form-control border-0" placeholder="Search posts by title or content..." value="<?= htmlspecialchars($search) ?>" aria-label="Search posts">
        <button class="btn btn-primary ms-2" type="submit" aria-label="Search"><i class="bi bi-search"></i></button>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card post-card">
                <div class="card-body">
                    <div class="post-title"><i class="bi bi-file-earmark-text"></i> <?= htmlspecialchars($row['title']) ?></div>
                    <div class="post-meta">
                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($row['username']) ?>
                        &nbsp; | &nbsp;
                        <i class="bi bi-calendar-event"></i> <?= htmlspecialchars($row['created_at']) ?>
                    </div>
                    <div class="post-content"><?= nl2br(htmlspecialchars($row['content'])) ?></div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-posts"><i class="bi bi-emoji-frown"></i> No posts found.</div>
    <?php endif; ?>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=1" aria-label="First">
                        <span aria-hidden="true">&laquo;&laquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $totalPages ?>" aria-label="Last">
                        <span aria-hidden="true">&raquo;&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</body>
</html>