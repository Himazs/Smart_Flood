<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
            <i class="bi bi-water"></i> Smart Flood
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (isset($_SESSION['user_type'])): ?>
                    <?php if ($_SESSION['user_type'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/donations.php">Donations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/safe_routes.php">Safe Routes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/users.php">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/emergencies.php">Emergencies</a>
                        </li>
                    <?php elseif ($_SESSION['user_type'] == 'government'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="government/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="government/aid_needs.php">Aid Needs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="government/stuck_individuals.php">Stuck Individuals</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="government/boats.php">Boats</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="government/rescue_teams.php">Rescue Teams</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_name'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['user_name']; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>