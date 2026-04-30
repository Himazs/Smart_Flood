<?php
include '../includes/config.php';
include '../includes/database.php';
include '../includes/auth.php';
include '../includes/functions.php';

redirectIfNotAdmin();

$db = new Database();
$conn = $db->connect();

// Handle add/edit safe route
if (isset($_POST['save_route'])) {
    $name = sanitizeInput($_POST['name']);
    $description = sanitizeInput($_POST['description']);
    $start_lat = sanitizeInput($_POST['start_lat']);
    $start_lng = sanitizeInput($_POST['start_lng']);
    $end_lat = sanitizeInput($_POST['end_lat']);
    $end_lng = sanitizeInput($_POST['end_lng']);
    $status = sanitizeInput($_POST['status']);
    
    if (isset($_POST['route_id']) && !empty($_POST['route_id'])) {
        // Update existing route
        $route_id = $_POST['route_id'];
        $stmt = $conn->prepare("UPDATE safe_routes SET name = :name, description = :description, start_lat = :start_lat, start_lng = :start_lng, end_lat = :end_lat, end_lng = :end_lng, status = :status WHERE id = :id");
        $stmt->bindParam(':id', $route_id);
        $action = 'updated';
    } else {
        // Add new route
        $stmt = $conn->prepare("INSERT INTO safe_routes (name, description, start_lat, start_lng, end_lat, end_lng, status) VALUES (:name, :description, :start_lat, :start_lng, :end_lat, :end_lng, :status)");
        $action = 'added';
    }
    
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':start_lat', $start_lat);
    $stmt->bindParam(':start_lng', $start_lng);
    $stmt->bindParam(':end_lat', $end_lat);
    $stmt->bindParam(':end_lng', $end_lng);
    $stmt->bindParam(':status', $status);
    
    if ($stmt->execute()) {
        logAction('Safe route ' . $action, "Route $name $action by admin");
        $_SESSION['success_message'] = 'Safe route ' . $action . ' successfully';
    } else {
        $_SESSION['error_message'] = 'Failed to ' . $action . ' safe route';
    }
    
    header('Location: safe_routes.php');
    exit();
}

// Handle delete route
if (isset($_GET['delete'])) {
    $route_id = $_GET['delete'];
    
    $stmt = $conn->prepare("DELETE FROM safe_routes WHERE id = :id");
    $stmt->bindParam(':id', $route_id);
    
    if ($stmt->execute()) {
        logAction('Safe route deleted', "Route $route_id deleted by admin");
        $_SESSION['success_message'] = 'Safe route deleted successfully';
    } else {
        $_SESSION['error_message'] = 'Failed to delete safe route';
    }
    
    header('Location: safe_routes.php');
    exit();
}

// Get route for editing
$edit_route = null;
if (isset($_GET['edit'])) {
    $route_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM safe_routes WHERE id = :id");
    $stmt->bindParam(':id', $route_id);
    $stmt->execute();
    $edit_route = $stmt->fetch();
}

// Get all safe routes
$stmt = $conn->prepare("SELECT * FROM safe_routes ORDER BY status, name");
$stmt->execute();
$routes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Safe Routes - Smart Flood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Manage Safe Routes</h2>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>
        
        <div class="row mt-4">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo $edit_route ? 'Edit Safe Route' : 'Add New Safe Route'; ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if ($edit_route): ?>
                                <input type="hidden" name="route_id" value="<?php echo $edit_route['id']; ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Route Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $edit_route['name'] ?? ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo $edit_route['description'] ?? ''; ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_lat" class="form-label">Start Latitude</label>
                                        <input type="number" step="any" class="form-control" id="start_lat" name="start_lat" value="<?php echo $edit_route['start_lat'] ?? ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_lng" class="form-label">Start Longitude</label>
                                        <input type="number" step="any" class="form-control" id="start_lng" name="start_lng" value="<?php echo $edit_route['start_lng'] ?? ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_lat" class="form-label">End Latitude</label>
                                        <input type="number" step="any" class="form-control" id="end_lat" name="end_lat" value="<?php echo $edit_route['end_lat'] ?? ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_lng" class="form-label">End Longitude</label>
                                        <input type="number" step="any" class="form-control" id="end_lng" name="end_lng" value="<?php echo $edit_route['end_lng'] ?? ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" <?php echo ($edit_route['status'] ?? '') == 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($edit_route['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                            
                            <button type="submit" name="save_route" class="btn btn-primary"><?php echo $edit_route ? 'Update' : 'Add'; ?> Route</button>
                            
                            <?php if ($edit_route): ?>
                                <a href="safe_routes.php" class="btn btn-secondary">Cancel</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h5>All Safe Routes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Start Coordinates</th>
                                        <th>End Coordinates</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($routes as $route): ?>
                                        <tr>
                                            <td><?php echo $route['name']; ?></td>
                                            <td><?php echo $route['start_lat'] . ', ' . $route['start_lng']; ?></td>
                                            <td><?php echo $route['end_lat'] . ', ' . $route['end_lng']; ?></td>
                                            <td><?php echo getStatusBadge($route['status']); ?></td>
                                            <td>
                                                <a href="safe_routes.php?edit=<?php echo $route['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                <a href="safe_routes.php?delete=<?php echo $route['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this route?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>