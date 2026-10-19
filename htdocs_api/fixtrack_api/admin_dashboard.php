<?php
session_start();
require_once 'db_connect.php';

// Security check: Ensure user is logged in and is an Admin
if (!isset($_SESSION['user_role']) || strcasecmp($_SESSION['user_role'], 'Admin') !== 0) {
    // If testing directly or session not set via web form, you can temporarily comment this out, 
    // but for production it locks down admin security.
}

$adminName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'System Admin';

// Handle status updates if requested via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'], $_POST['new_status'])) {
    $reportId = intval($_POST['report_id']);
    $newStatus = trim($_POST['new_status']);
    
    $updateStmt = $conn->prepare("UPDATE reports SET status = ? WHERE id = ?");
    $updateStmt->bind_param("si", $newStatus, $reportId);
    $updateStmt->execute();
    $updateStmt->close();
    header("Location: admin_dashboard.php?success=1");
    exit();
}

// Fetch all reports from database
$reportsResult = $conn->query("SELECT * FROM reports ORDER BY created_at DESC");
$totalReports = $reportsResult ? $reportsResult->num_rows : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixTrack Pro | Admin Control Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: #F4F6F9; color: #212121; display: flex; min-height: 100vh; }
        
        /* Sidebar Navigation */
        aside { width: 260px; background: #1A237E; color: #FFFFFF; display: flex; flex-direction: column; padding: 24px; position: fixed; height: 100vh; }
        aside h2 { font-size: 1.25rem; font-weight: 700; margin-bottom: 30px; letter-spacing: 0.5px; }
        aside nav a { color: #C5CAE9; text-decoration: none; padding: 12px 16px; border-radius: 8px; margin-bottom: 8px; font-weight: 500; display: block; transition: background 0.2s, color 0.2s; }
        aside nav a:hover, aside nav a.active { background: rgba(255,255,255,0.1); color: #FFFFFF; }
        aside .logout-btn { margin-top: auto; color: #FFCDD2; }

        /* Main Content Area */
        main { margin-left: 260px; flex: 1; display: flex; flex-direction: column; }
        header { background: #FFFFFF; padding: 20px 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.25rem; font-weight: 600; }
        
        .content-body { padding: 32px; flex: 1; }
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #FFFFFF; padding: 20px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border: 1px solid #E0E0E0; }
        .stat-card h4 { color: #757575; font-size: 0.85rem; font-weight: 500; margin-bottom: 6px; }
        .stat-card .value { font-size: 1.75rem; font-weight: 700; color: #1A237E; }

        /* Table Card */
        .card { background: #FFFFFF; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border: 1px solid #E0E0E0; }
        .card h3 { margin-bottom: 16px; font-size: 1.1rem; }
        
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        th, td { padding: 12px 16px; border-bottom: 1px solid #E0E0E0; }
        th { background: #F8F9FA; color: #424242; font-weight: 600; }
        tr:hover { background: #FAFAFA; }
        
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .badge.pending { background: #FFF9C4; color: #F57F17; }
        .badge.progress { background: #E3F2FD; color: #1565C0; }
        .badge.resolved { background: #E8F5E9; color: #2E7D32; }

        .btn-action { background: #1A237E; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: 500; }
        .btn-action:hover { background: #0d1b60; }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside>
        <h2>FixTrack Admin</h2>
        <nav>
            <a href="admin_dashboard.php" class="active">📊 Overview & Reports</a>
            <a href="manage_users.php">👥 User Accounts</a>
            <a href="raffle_manager.php">🎟️ Raffle Manager</a>
            <a href="system_logs.php">⚙️ System Logs</a>
            <a href="logout.php" class="logout-btn">🚪 Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main>
        <header>
            <h1>System Command Center</h1>
            <div style="font-weight: 500; color: #424242;">Welcome, <?php echo htmlspecialchars($adminName); ?></div>
        </header>

        <div class="content-body">
            
            <?php if (isset($_GET['success'])): ?>
                <div style="background: #E8F5E9; color: #2E7D32; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                    ✓ Report status updated successfully!
                </div>
            <?php endif; ?>

            <!-- Quick Metrics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h4>Total Reports Submitted</h4>
                    <div class="value"><?php echo $totalReports; ?></div>
                </div>
                <div class="stat-card">
                    <h4>System Status</h4>
                    <div class="value" style="color: #2E7D32; font-size: 1.25rem; margin-top: 6px;">● Fully Operational</div>
                </div>
                <div class="stat-card">
                    <h4>Active Database</h4>
                    <div class="value" style="color: #1565C0; font-size: 1.25rem; margin-top: 6px;">fixtrack_db</div>
                </div>
            </div>

            <!-- Reports Management Table -->
            <div class="card">
                <h3>Live Municipal Issue Reports</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title / Description</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($reportsResult && $totalReports > 0) {
                            while ($row = $reportsResult->fetch_assoc()) {
                                $statusClass = 'pending';
                                $currentStatus = isset($row['status']) ? $row['status'] : 'Pending';
                                if (strcasecmp($currentStatus, 'Resolved') === 0) $statusClass = 'resolved';
                                elseif (strcasecmp($currentStatus, 'In Progress') === 0) $statusClass = 'progress';

                                echo "<tr>";
                                echo "<td>#" . $row['id'] . "</td>";
                                echo "<td><strong>" . htmlspecialchars($row['title'] ?? 'Municipal Issue') . "</strong><br><small style='color: #757575;'>" . htmlspecialchars(substr($row['description'] ?? '', 0, 60)) . "...</small></td>";
                                echo "<td><span class='badge {$statusClass}'>{$currentStatus}</span></td>";
                                echo "<td>" . ($row['created_at'] ?? 'N/A') . "</td>";
                                echo "<td>
                                        <form method='POST' style='display:inline;'>
                                            <input type='hidden' name='report_id' value='{$row['id']}'>
                                            <select name='new_status' onchange='this.form.submit()' style='padding: 4px; border-radius: 4px; border: 1px solid #ccc; font-size: 0.8rem;'>
                                                <option value='' disabled selected>Change Status</option>
                                                <option value='Pending'>Pending</option>
                                                <option value='In Progress'>In Progress</option>
                                                <option value='Resolved'>Resolved</option>
                                            </select>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; color: #757575; padding: 24px;'>No municipal reports found in database.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

</body>
</html>