<?php
include '../includes/config.php';
include '../includes/database.php';
include '../includes/auth.php';
include '../includes/functions.php';

redirectIfNotGovernment();

$db = new Database();
$conn = $db->connect();

// Get counts for dashboard
$counts = [];

// Active emergencies count
$stmt = $conn->query("SELECT COUNT(*) as count FROM emergencies WHERE status = 'active'");
$counts['active_emergencies'] = $stmt->fetch()['count'];

// Pending aid needs count
$stmt = $conn->query("SELECT COUNT(*) as count FROM donations WHERE status = 'pending'");
$counts['pending_aid'] = $stmt->fetch()['count'];

// Affected people count
$stmt = $conn->query("SELECT COUNT(*) as count FROM affected_people");
$counts['affected_people'] = $stmt->fetch()['count'];

// Available boats count
$stmt = $conn->query("SELECT COUNT(*) as count FROM boats WHERE status = 'available'");
$counts['available_boats'] = $stmt->fetch()['count'];

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

// Get recent aid needs
$stmt = $conn->query("
    SELECT d.*, u.name as user_name 
    FROM donations d 
    LEFT JOIN users u ON d.user_id = u.id 
    WHERE d.status = 'pending' 
    ORDER BY d.created_at DESC 
    LIMIT 5
");
$recent_aid = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Government Dashboard - Smart Flood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Government Dashboard</h2>
        
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Active Emergencies</h5>
                        <p class="card-text display-4"><?php echo $counts['active_emergencies']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Pending Aid</h5>
                        <p class="card-text display-4"><?php echo $counts['pending_aid']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Affected People</h5>
                        <p class="card-text display-4"><?php echo $counts['affected_people']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Available Boats</h5>
                        <p class="card-text display-4"><?php echo $counts['available_boats']; ?></p>
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
                            <a href="aid_needs.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-heart"></i> Manage Aid Needs
                            </a>
                            <a href="stuck_individuals.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-person-check"></i> View Stuck Individuals
                            </a>
                            <a href="boats.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-boat"></i> Manage Boats
                            </a>
                            <a href="rescue_teams.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-people"></i> Manage Rescue Teams
                            </a>
                            <a href="affected_people.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-person-plus"></i> Manage Affected People
                            </a>
                            <a href="shelters.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-house"></i> Manage Shelters
                            </a>
                            <a href="messages.php" class="btn btn-outline-primary btn-block">
                                <i class="bi bi-chat"></i> Send Messages
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Recent Emergencies</h5>
                        <a href="stuck_individuals.php" class="btn btn-sm btn-primary">View All</a>
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
                        <h5>Recent Aid Needs</h5>
                        <a href="aid_needs.php" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (count($recent_aid) > 0): ?>
                            <div class="list-group">
                                <?php foreach ($recent_aid as $aid): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo $aid['name']; ?></h6>
                                            <small><?php echo formatDate($aid['created_at'], 'M j, H:i'); ?></small>
                                        </div>
                                        <p class="mb-1">Contact: <?php echo $aid['contact']; ?></p>
                                        <p class="mb-1"><?php echo $aid['description']; ?></p>
                                        <small class="text-warning">Status: <?php echo getStatusBadge($aid['status']); ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No pending aid needs</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>