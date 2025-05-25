<?php
include 'db.php';

// Fetch site settings
$stmt = $conn->prepare("SELECT site_name FROM settings WHERE id = 1");
$stmt->execute();
$stmt->bind_result($site_name);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($site_name); ?></title>
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($site_name); ?></h1>
    </header>
</body>
</html>