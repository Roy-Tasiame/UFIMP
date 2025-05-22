<?php
include '../../functions/totalforms.php';
// Additional functions for retrieving submissions
// include '../../functions/submissions.php';

// Sample function to get submissions (implement this in submissions.php)
function getSubmissions($conn, $formId = null) {
    // Query to fetch submissions, joining with relevant tables as needed
    // Return submissions array
}

// Get form ID from URL parameter if available
$formId = isset($_GET['form_id']) ? $_GET['form_id'] : null;

// Get submissions based on form ID or get all submissions if no ID specified
$submissions = getSubmissions($conn, $formId);

// Get list of forms for the filter dropdown
$forms = getAllForms($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Submissions - Form Creator</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="submission.css">
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
                <a href="dashboard.php" class="nav-link">
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
                <a href="viewsubmissions.php" class="nav-link active">
                    <i class="fas fa-inbox"></i>
                    Submissions
                </a>
            </div>
            <div class="nav-item">
                <a href="analytics.php" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    Analytics
                </a>
            </div>
            <div class="nav-item">
                <a href="departments.php" class="nav-link">
                    <i class="fas fa-building"></i>
                    Departments
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
            <div class="page-header">
                <h1 class="page-title">Form Submissions</h1>
                <div class="header-actions">
                    <div class="form-filter">
                        <select id="formSelector" onchange="filterSubmissions()">
                            <option value="">All Forms</option>
                            <?php foreach($forms as $form): ?>
                                <option value="<?php echo $form['id']; ?>" <?php echo ($formId == $form['id']) ? 'selected' : ''; ?>>
                                    <?php echo $form['title']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="export-button">
                        <button onclick="exportToCSV()">
                            <i class="fas fa-download"></i> Export to CSV
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submissions Stats Cards -->
            <div class="analytics-grid">
                <div class="analytics-card">
                    <div class="card-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <div class="card-content">
                        <h3>Total Submissions</h3>
                        <p class="card-value"><?php echo count($submissions); ?></p>
                        <p class="card-subtext">Across all forms</p>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="card-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="card-content">
                        <h3>Recent Submissions</h3>
                        <p class="card-value"><?php echo countRecentSubmissions($conn); ?></p>
                        <p class="card-subtext">In the last 7 days</p>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="card-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-content">
                        <h3>Completion Rate</h3>
                        <p class="card-value"><?php echo calculateCompletionRate($conn); ?>%</p>
                        <p class="card-subtext">Form completion percentage</p>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="card-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="card-content">
                        <h3>Active Forms</h3>
                        <p class="card-value"><?php echo countActiveForms($conn); ?></p>
                        <p class="card-subtext">Currently accepting submissions</p>
                    </div>
                </div>
            </div>

            <!-- Submissions Table -->
            <div class="table-container">
                <div class="table-header">
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Search submissions..." onkeyup="searchSubmissions()">
                    </div>
                    <div class="table-actions">
                        <button class="action-btn" id="bulkDelete" disabled>
                            <i class="fas fa-trash"></i> Delete Selected
                        </button>
                        <button class="action-btn" onclick="refreshTable()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
                
                <table class="submissions-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"></th>
                            <th>ID</th>
                            <th>Form Name</th>
                            <th>Submitter</th>
                            <th>Email</th>
                            <th>Submission Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="submissionsTableBody">
                        <?php if(empty($submissions)): ?>
                            <tr>
                                <td colspan="8" class="no-data">No submissions found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($submissions as $submission): ?>
                                <tr>
                                    <td><input type="checkbox" class="submission-checkbox" value="<?php echo $submission['id']; ?>"></td>
                                    <td><?php echo $submission['id']; ?></td>
                                    <td><?php echo $submission['form_title']; ?></td>
                                    <td><?php echo $submission['submitter_name']; ?></td>
                                    <td><?php echo $submission['submitter_email']; ?></td>
                                    <td><?php echo date('M d, Y H:i', strtotime($submission['submission_date'])); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo strtolower($submission['status']); ?>">
                                            <?php echo $submission['status']; ?>
                                        </span>
                                    </td>
                                    <td class="actions-cell">
                                        <button class="icon-btn view-btn" onclick="viewSubmission(<?php echo $submission['id']; ?>)" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="icon-btn download-btn" onclick="downloadSubmission(<?php echo $submission['id']; ?>)" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="icon-btn delete-btn" onclick="deleteSubmission(<?php echo $submission['id']; ?>)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <div class="pagination">
                    <button class="pagination-btn" onclick="changePage(-1)">
                        <i class="fas fa-chevron-left"></i> Previous
                    </button>
                    <div class="page-info">
                        Page <span id="currentPage">1</span> of <span id="totalPages">1</span>
                    </div>
                    <button class="pagination-btn" onclick="changePage(1)">
                        Next <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Submission Details Modal -->
    <div id="submissionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Submission Details</h2>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body" id="submissionDetails">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button onclick="closeModal()">Close</button>
                <button class="btn-primary" onclick="downloadCurrentSubmission()">Download</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="submission.js"></script>
</body>
</html>