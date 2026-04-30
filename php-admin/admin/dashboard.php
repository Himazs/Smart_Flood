<?php
include '../includes/config.php';
include '../includes/database.php';
include '../includes/auth.php';
include '../includes/functions.php';

redirectIfNotAdmin();

$db = new Database();
$conn = $db->connect();

// Get counts for dashboard
$counts = [];

// Active emergencies count
$stmt = $conn->query("SELECT COUNT(*) as count FROM emergencies WHERE status = 'active'");
$counts['active_emergencies'] = $stmt->fetch()['count'];

// Pending flood reports count
$stmt = $conn->query("SELECT COUNT(*) as count FROM flood_reports WHERE status = 'pending'");
$counts['pending_reports'] = $stmt->fetch()['count'];

// Pending donations count
$stmt = $conn->query("SELECT COUNT(*) as count FROM donations WHERE status = 'pending'");
$counts['pending_donations'] = $stmt->fetch()['count'];

// Public users count
$stmt = $conn->query("SELECT COUNT(*) as count FROM users WHERE user_type = 'public'");
$counts['public_users'] = $stmt->fetch()['count'];

// Volunteer users count
$stmt = $conn->query("SELECT COUNT(*) as count FROM users WHERE user_type = 'volunteer'");
$counts['volunteer_users'] = $stmt->fetch()['count'];

// Get recent emergencies
$stmt = $conn->query("
    SELECT e.*, u.name as user_name 
    FROM emergencies e 
    JOIN users u ON e.user_id = u.id 
    WHERE e.status = 'active' 
    ORDER BY e.created_at DESC 
    LIMIT 5
");
$recent_emergencies = $stmt->fetchAll();

// Get recent flood reports
$stmt = $conn->query("
    SELECT fr.*, u.name as user_name 
    FROM flood_reports fr 
    JOIN users u ON fr.user_id = u.id 
    WHERE fr.status = 'pending' 
    ORDER BY fr.created_at DESC 
    LIMIT 5
");
$recent_reports = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Flood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Admin Dashboard</h2>
        
        <div class="row mt-4">
            <div class="col-md-2">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Active Emergencies</h5>
                        <p class="card-text display-4"><?php echo $counts['active_emergencies']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Pending Reports</h5>
                        <p class="card-text display-4"><?php echo $counts['pending_reports']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Pending Donations</h5>
                        <p class="card-text display-4"><?php echo $counts['pending_donations']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Public Users</h5>
                        <p class="card-text display-4"><?php echo $counts['public_users']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Volunteers</h5>
                        <p class="card-text display-4"><?php echo $counts['volunteer_users']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="donations.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-cash"></i> Manage Donations
                            </a>
                            <a href="safe_routes.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-signpost"></i> Manage Safe Routes
                            </a>
                            <a href="aid_info.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-info-circle"></i> Manage Aid Information
                            </a>
                            <a href="users.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-people"></i> Manage Users
                            </a>
                            <a href="emergencies.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-exclamation-triangle"></i> Manage Emergencies
                            </a>
                            <a href="flood_reports.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-water"></i> Manage Flood Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Recent Emergencies</h5>
                        <a href="emergencies.php" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (count($recent_emergencies) > 0): ?>
                            <div class="list-group">
                                <?php foreach ($recent_emergencies as $emergency): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo $emergency['user_name']; ?></h6>
                                            <small><?php echo formatDate($emergency['created_at'], 'M j, H:i'); ?></small>
                                        </div>
                                        <p class="mb-1">Location: <?php echo $emergency['latitude'] . ', ' . $emergency['longitude']; ?></p>
                                        <small class="text-danger">Status: <?php echo getStatusBadge($emergency['status']); ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No active emergencies</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Recent Flood Reports</h5>
                        <a href="flood_reports.php" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (count($recent_reports) > 0): ?>
                            <div class="list-group">
                                <?php foreach ($recent_reports as $report): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo $report['title']; ?></h6>
                                            <small><?php echo formatDate($report['created_at'], 'M j, H:i'); ?></small>
                                        </div>
                                        <p class="mb-1">Reported by: <?php echo $report['user_name']; ?></p>
                                        <p class="mb-1">Severity: <?php echo getSeverityBadge($report['severity']); ?></p>
                                        <small class="text-warning">Status: <?php echo getStatusBadge($report['status']); ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No pending flood reports</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>