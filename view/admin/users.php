<?php
include '../../functions/total_users.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
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
                <a href="users.php" class="tab-link active">Users</a>
                <a href="departments.php" class="tab-link">Departments</a>
            </div>

            <div class="page-header">
                <h1 class="page-title">User Management</h1>
                <div class="actions">
                    <button id="addUserBtn" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add User
                    </button>
                </div>
            </div>

            <!-- Add User Modal -->
            <div id="userModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Add New User</h2>
                    <form id="userForm" method="post" action="../../actions/add_user.php">
                        <div class="form-group">
                            <label for="firstName">Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <input type="hidden" name = "password" value = "ChangeMeNow">
                        <div class="form-group">
                            <label for="userRole">Role</label>
                            <select id="userRole" name="role" required>
                                <option value="">Select a role</option>
                                <option value="student">Student</option>
                                <option value="department">Department</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Save User</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-container">
                    <label for="roleFilter">Filter by Role:</label>
                    <select id="roleFilter">
                        <option value="all">All Roles</option>
                        <option value="student">Students</option>
                        <option value="department">Departments</option>
                        <option value="admin">Admins</option>
                    </select>
                </div>
                <div class="filter-container">
                    <label for="deptFilter">Filter by Department:</label>
                    <select id="deptFilter">
                        <option value="all">All Departments</option>
                    </select>
                </div>
                <div class="search-box">
                    <input type="text" id="userSearch" placeholder="Search users...">
                    <i class="fas fa-search"></i>
                </div>
            </div>


                        <!-- User Statistics -->
            <div class="analytics-grid">
                <div class="analytics-card">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-content">
                        <h3>Total Users</h3>
                        <p class="card-value"><?php echo countTotalUsers($conn); ?></p>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="card-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="card-content">
                        <h3>Students</h3>
                        <p class="card-value"><?php echo countUsersByRole($conn, 'student'); ?></p>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="card-content">
                        <h3>Departments</h3>
                        <p class="card-value"><?php echo countUsersByRole($conn, 'department'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Users List -->
            <div class="card-container">
                <div class="card">
                    <div class="card-header">
                        <h2>All Users</h2>
                    </div>
                    <div class="card-body">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Sample code - replace with actual user fetching code
                                $users = getAllUsers($conn);
                                if ($users && count($users) > 0) {
                                    foreach ($users as $user) {
                                        echo "<tr>";
                                        echo "<td>{$user['name']}</td>";
                                        echo "<td>{$user['email']}</td>";
                                        echo "<td>{$user['role']}</td>";
                                        
                                        // Display either student ID or faculty department
                                        echo "<td class='actions-cell'>";
                                        echo "<button class='action-btn edit-btn' data-id='{$user['user_id']}'><i class='fas fa-edit'></i></button>";
  
                                        echo "<button class='action-btn delete-btn' data-id='{$user['user_id']}'><i class='fas fa-trash'></i></button>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='no-data'>No users found</td></tr>";
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