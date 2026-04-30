<?php
include '../includes/config.php';
include '../includes/database.php';
include '../includes/auth.php';
include '../includes/functions.php';

redirectIfNotAdmin();

$db = new Database();
$conn = $db->connect();

// Handle status update
if (isset($_POST['update_status'])) {
    $donation_id = $_POST['donation_id'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE donations SET status = :status WHERE id = :id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $donation_id);
    
    if ($stmt->execute()) {
        logAction('Donation status updated', "Donation $donation_id status changed to $status");
        $_SESSION['success_message'] = 'Donation status updated successfully';
    } else {
        $_SESSION['error_message'] = 'Failed to update donation status';
    }
    
    header('Location: donations.php');
    exit();
}

// Get all donations
$stmt = $conn->prepare("
    SELECT d.*, u.name as user_name 
    FROM donations d 
    LEFT JOIN users u ON d.user_id = u.id 
    ORDER BY d.created_at DESC
");
$stmt->execute();
$donations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Donations - Smart Flood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Manage Donations</h2>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>
        
        <div class="card mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Donor Name</th>
                                <th>Contact</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Submitted By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($donations as $donation): ?>
                                <tr>
                                    <td><?php echo $donation['id']; ?></td>
                                    <td><?php echo $donation['name']; ?></td>
                                    <td><?php echo $donation['contact']; ?></td>
                                    <td><?php echo $donation['description']; ?></td>
                                    <td><?php echo getStatusBadge($donation['status']); ?></td>
                                    <td><?php echo $donation['user_name'] ?? 'Unknown'; ?></td>
                                    <td><?php echo formatDate($donation['created_at']); ?></td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="donation_id" value="<?php echo $donation['id']; ?>">
                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="pending" <?php echo $donation['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="received" <?php echo $donation['status'] == 'received' ? 'selected' : ''; ?>>Received</option>
                                                <option value="distributed" <?php echo $donation['status'] == 'distributed' ? 'selected' : ''; ?>>Distributed</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>