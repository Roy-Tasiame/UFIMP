<?php
include '../../functions/total_users.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Department Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="admin.css">
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
                <a href="admindashboard.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="myforms.php" class="nav-link">
                    <i class="fas fa-file-alt"></i>
                    My Forms
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
                    <i class="fas fa-users"></i>
                    Users
                </a>
            </div>
            <div class="nav-item">
                <a href="departments.php" class="nav-link">
                    <i class="fas fa-building"></i>
                    Departments
                </a >
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
            <!-- Tabs Navigation -->
            <div class="admin-tabs">
                <a href="users.php" class="tab-link">Users</a>
                <a href="departments.php" class="tab-link active">Departments</a>
            </div>

            <div class="page-header">
                <h1 class="page-title">Department Management</h1>
                <div class="actions">
                    <button id="addDeptBtn" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Department
                    </button>
                </div>
            </div>

                        <!-- Department Statistics -->
                        <div class="analytics-grid">
                <div class="analytics-card">
                    <div class="card-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="card-content">
                        <h3>Total Departments</h3>
                        <p class="card-value"><?php echo countUsersByRole($conn, 'department'); ?></p>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="card-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="card-content">
                        <h3>Feedback per Department</h3>
                        <p class="card-value"><?php echo countUsersByRole($conn, 'department'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Add Department Modal -->
            <div id="deptModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Add New Department</h2>
                    <form id="deptForm" method="post" action="../../actions/add_dept.php">
                        <div class="form-group">
                            <label for="deptName">Department Name</label>
                            <input type="text" id="deptName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="deptEmail">Contact Email</label>
                            <input type="email" id="deptEmail" name="email">
                        </div>
                        <input type="hidden" name = "role" value = "department">
                        <input type="hidden" name = "password" value = "ChangeMeNow">
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Save Department</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Departments List -->
            <div class="card-container">
                <div class="card">
                    <div class="card-header">
                        <h2>All Departments</h2>
                        <div class="search-box">
                            <input type="text" id="deptSearch" placeholder="Search departments...">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Contact Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Sample code - replace with actual department fetching code
                                $departments = getAllDepartments($conn);
                                if ($departments && count($departments) > 0) {
                                    foreach ($departments as $dept) {
                                        echo "<tr>";
                                        echo "<td>{$dept['name']}</td>";
                                        echo "<td>{$dept['email']}</td>";
                                        echo "<td class='actions-cell'>";
                                        echo "<button class='action-btn edit-btn' data-id='{$dept['user_id']}'><i class='fas fa-edit'></i></button>";
                                        echo "<button class='action-btn delete-btn' data-id='{$dept['user_id']}'><i class='fas fa-trash'></i></button>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='no-data'>No departments found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="admin.js"></script>
</body>
</html>