<?php
// Enable PHP error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "db_config.php";

$message = "";
$messageType = "";

// Handle posting a new announcement
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'add_notification') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO notifications (title, message) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $title, $content);
            if ($stmt->execute()) {
                $message = "Announcement published successfully to mobile app!";
                $messageType = "success";
            } else {
                $message = "Failed to publish announcement: " . $stmt->error;
                $messageType = "danger";
            }
            $stmt->close();
        } else {
            $message = "Prepare statement failed: " . $conn->error;
            $messageType = "danger";
        }
    } else {
        $message = "Title and Content are required fields.";
        $messageType = "warning";
    }
}

// Fetch registered users safely
$usersResult = $conn->query("SELECT * FROM users");

// Fetch broadcast notifications safely
$notifResult = $conn->query("SELECT * FROM notifications");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixTrack Pro - Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-brand { font-weight: 700; letter-spacing: 0.5px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .card-header { background-color: #ffffff; border-bottom: 1px solid #edf2f7; font-weight: 600; }
        .table-hover tbody tr:hover { background-color: #f1f5f9; }
    </style>
</head>
<body>

    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">🛠️ FixTrack Pro Admin</a>
            <span class="navbar-text text-light small">
                Live Server: <code>10.0.0.4/fixtrack_api/</code>
            </span>
        </div>
    </nav>

    <div class="container pb-5">

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">

            <!-- Left Column: Post Announcement Form -->
            <div class="col-md-5">
                <div class="card p-3">
                    <div class="card-header pb-3">
                        <h5 class="card-title m-0">📢 Broadcast New Announcement</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="dashboard.php">
                            <input type="hidden" name="action" value="add_notification">
                            <div class="mb-3">
                                <label for="title" class="form-label font-semibold">Announcement Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Scheduled Maintenance" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Message Details</label>
                                <textarea class="form-control" id="content" name="content" rows="4" placeholder="Type notification message..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Publish to Mobile App</button>
                        </form>
                    </div>
                </div>

                <!-- Live Broadcast History -->
                <div class="card p-3 mt-4">
                    <div class="card-header pb-3">
                        <h5 class="card-title m-0">📋 Live Broadcast History</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php if ($notifResult && $notifResult->num_rows > 0): ?>
                                <?php while ($notif = $notifResult->fetch_assoc()): ?>
                                    <li class="list-group-item py-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <strong class="text-dark"><?= htmlspecialchars($notif['title'] ?? 'Announcement') ?></strong>
                                            <small class="text-muted"><?= htmlspecialchars($notif['created_at'] ?? 'Recently') ?></small>
                                        </div>
                                        <p class="mb-0 text-secondary small"><?= htmlspecialchars($notif['message'] ?? $notif['content'] ?? '') ?></p>
                                    </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="list-group-item text-muted text-center py-3">No announcements posted yet.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Column: Registered Users Table -->
            <div class="col-md-7">
                <div class="card p-3">
                    <div class="card-header pb-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title m-0">👥 Registered Users (fixtrack_db)</h5>
                        <span class="badge bg-primary rounded-pill"><?= $usersResult ? $usersResult->num_rows : 0 ?> Accounts</span>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Email Address</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($usersResult && $usersResult->num_rows > 0): ?>
                                    <?php while ($user = $usersResult->fetch_assoc()): ?>
                                        <tr>
                                            <td><code>#<?= $user['user_id'] ?? $user['id'] ?? 1 ?></code></td>
                                            <td class="fw-semibold"><?= htmlspecialchars($user['full_name'] ?? $user['name'] ?? 'User') ?></td>
                                            <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                                            <td>
                                                <span class="badge bg-<?= isset($user['role']) && $user['role'] === 'admin' ? 'danger' : 'secondary' ?>">
                                                    <?= htmlspecialchars(strtoupper($user['role'] ?? 'USER')) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No users registered in database.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>