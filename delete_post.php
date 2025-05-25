<!-- filepath: c:\xampp\htdocs\ApexPlanet_Internship_May_25_Proj\Task_4\Security_Enhancements\delete_post.php -->
<?php
include "db.php";
include "auth.php"; // Ensure the user is authenticated

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $postId = (int) $_GET["id"];
    $userId = $_SESSION["user_id"];

    // Check if the post exists
    $check = $conn->prepare("SELECT id FROM posts WHERE id = ?");
    $check->bind_param("i", $postId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // Post exists, now check if the user owns it
        $check_owner = $conn->prepare("SELECT id FROM posts WHERE id = ? AND user_id = ?");
        $check_owner->bind_param("ii", $postId, $userId);
        $check_owner->execute();
        $check_owner->store_result();

        if ($check_owner->num_rows > 0) {
            // User owns the post, proceed with deletion
            $delete = $conn->prepare("DELETE FROM posts WHERE id = ?");
            $delete->bind_param("i", $postId);
            if ($delete->execute()) {
                header("Location: editor_dashboard.php?msg=Post+deleted+successfully");
                exit;
            } else {
                header("Location: editor_dashboard.php?error=Failed+to+delete+post");
                exit;
            }
        } else {
            // Post exists but doesn't belong to the user
            header("Location: editor_dashboard.php?error=You+are+not+allowed+to+delete+this+post");
            exit;
        }
    } else {
        // Post does not exist
        header("Location: editor_dashboard.php?error=Post+not+found");
        exit;
    }
} else {
    // Invalid post ID
    header("Location: editor_dashboard.php?error=Invalid+post+ID");
    exit;
}
?>
