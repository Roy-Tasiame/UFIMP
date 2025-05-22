<?php
include '../../functions/totalforms.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Form Creator</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../assets/img/logonobg.png" alt="Ashesi Logo" class="sidebar-logo">
                <h2>Ashesi Forms</h2>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="myforms.php" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        All Forms
                    </a>
                </div>
                <div class="nav-item">
                    <a href="analytics.php" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        Analytics
                    </a>
                </div>
                <div class="nav-item">
                    <a href="users.php" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        Users
                    </a>
                </div>
                <div class="nav-item">
                    <a href="settings.php" class="nav-link">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="dashboard">
            <main class="main-content">
                <div class="page-header">
                    <h1 class="page-title">Dashboard</h1>
                    <div class = "new-form">
                    <a href = 'createform.php'><button>Create New Form</button></a>
                </div>
                </div>

                <!-- Analytics Cards -->
                <div class="analytics-grid">
                    <div class="analytics-card">
                        <div class="card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="card-content">
                            <h3>Total Forms Created</h3>
                            <p class="card-value"><?php echo countTotalForms($conn); ?></p>
                            <p class="card-subtext">+0 this week</p>
                        </div>
                    </div>

                    <div class="analytics-card">
                        <div class="card-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-content">
                            <h3>Total Submissions</h3>
                            <p class="card-value"><?php echo countTotalResponses($conn); ?></p>
                            <p class="card-subtext">+0 this week</p>
                        </div>
                    </div>

                    <div class="analytics-card">
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-content">
                            <h3>Active Users</h3>
                            <p class="card-value">0</p>
                            <p class="card-subtext">+0 this week</p>
                        </div>
                    </div>

                    <div class="analytics-card">
                        <div class="card-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="card-content">
                            <h3>Engagement Rate</h3>
                            <p class="card-value">0%</p>
                            <p class="card-subtext">+0% this week</p>
                        </div>
                    </div>
                </div>

            </main>
        </div>

    <!-- Chart.js Library -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="dashboard.js"></script>
</body>
</html>