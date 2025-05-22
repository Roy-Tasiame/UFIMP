<?php
include '../../functions/totalforms.php';
$newFormsInLast7Days = countNewForms($conn, 7);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Forms - Ashesi Form Builder</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="nav.css">
    <style>
        :root {
            --maroon: #800000;
            --maroon-light:rgb(215, 56, 56);
            --border-color: #ddd;
            --light-gray: #f8f9fa;
            --dark: #333;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #a06666;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1100px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-left: 250px;
        }
        
        /* .sidebar {
            width: 230px;
            background-color: var(--maroon);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 15px 0;
        }
        
        .sidebar-header {
            padding: 0 15px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-logo {
            width: 120px;
            margin-bottom: 10px;
        } */
        
        .page-header {
            background-color: var(--maroon);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-header h1 {
            font-size: 24px;
            font-weight: 400;
            margin: 0;
        }
        
        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-primary {
            background-color: white;
            color: var(--maroon);
        }
        
        .btn-success {
            background-color: var(--maroon);
            color: white;
        }
        
        .btn-info {
            background-color: var(--info);
            color: white;
        }
        
        .btn-warning {
            background-color: var(--warning);
            color: white;
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .forms-container {
            padding: 20px;
        }
        
        .search-filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .search-box {
            display: flex;
            align-items: center;
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 8px 12px;
            width: 300px;
        }
        
        .search-box input {
            border: none;
            outline: none;
            padding: 5px;
            width: 100%;
            font-size: 14px;
        }
        
        .search-box i {
            color: #999;
            margin-right: 8px;
        }
        
        .filter-options {
            display: flex;
            gap: 10px;
        }
        
        .filter-select {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background-color: white;
            font-size: 14px;
        }
        
        .forms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .form-card {
            background-color: white;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            overflow: visible;
            transition: all 0.3s;
            z-index: 0;
            position: relative;            

        }
        
        .form-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .form-card-header {
            padding: 12px 15px;
            background-color: var(--light-gray);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .form-card-title {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .form-card-body {
            padding: 15px;
        }
        
        .form-card-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .stat-item {
            text-align: center;
            flex: 1;
        }
        
        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: var(--maroon);
        }
        
        .stat-label {
            font-size: 12px;
            color: #666;
        }
        
        .form-card-meta {
            font-size: 13px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 5px;
        }
        
        .form-card-actions {
            display: flex;
            justify-content: space-between;
            gap: 5px;
        }
        
        .form-card-actions .btn {
            flex: 1;
            padding: 8px;
            justify-content: center;
            font-size: 13px;
        }
        
        .status-badge {
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 12px;
            color: white;
        }
        
        .status-active {
            background-color: var(--success);
        }
        
        .status-draft {
            background-color: var(--warning);
            color: var(--dark);
        }
        
        .status-closed {
            background-color: var(--danger);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .empty-state-icon {
            font-size: 60px;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .empty-state-title {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 5px;
        }
        
        .page-item {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .page-item.active {
            background-color: var(--maroon);
            color: white;
            border-color: var(--maroon);
        }
        
        .page-item:hover:not(.active) {
            background-color: var(--light-gray);
        }
        
        .dashboard-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid var(--maroon);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .summary-card-title {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .summary-card-value {
            font-size: 24px;
            font-weight: bold;
            color: var(--dark);
        }
        
        .summary-card-trend {
            font-size: 12px;
            color: var(--success);
            display: flex;
            align-items: center;
            gap: 3px;
            margin-top: 5px;
        }
        
        .form-actions-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .form-card-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
            padding: 12px 16px;
            background-color: #f7f7f7;
            border-top: 1px solid #e0e0e0;
            border-radius: 0 0 6px 6px;
        }

        .form-card-actions .btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            font-size: 14px;
            font-weight: 500;
            background-color: var(--maroon-light); /* success green */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .form-card-actions .btn:hover {
            background-color: #218838; /* darker green on hover */
        }

        .form-card-actions .btn i {
            font-size: 14px;
        }
      
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .modal-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        
        .close-button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #999;
        }
        
        .modal-body {
            margin-bottom: 20px;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-top: 1px solid var(--border-color);
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../assets/img/logonobg.png" alt="Ashesi Logo" class="sidebar-logo" style="margin-top: 25px;">
            <h2>Ashesi Form</h2>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="admindashboard.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link active">
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
                    <i class="fas fa-chart-bar"></i>
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
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="page-header">
            <h1>My Forms</h1>
            <a href="createform.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Form
            </a>
        </div>

        <div class="forms-container">
            <!-- Dashboard Summary -->
            <div class="dashboard-summary">
                <div class="summary-card">
                    <div class="summary-card-title">Total Forms</div>
                    <div class="summary-card-value"><?php echo countTotalForms($conn); ?></div>
                    <div class="summary-card-trend">
                        <i class="fas fa-arrow-up"></i> 20% from last week
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-card-title">Total Responses</div>
                    <div class="summary-card-value"><?php echo countTotalResponses($conn); ?></div>
                    <div class="summary-card-trend">
                        <i class="fas fa-arrow-up"></i> 15% from last week
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-card-title">Recent Forms</div>
                    <div class="summary-card-value"><?php echo $newFormsInLast7Days; ?></div>
                    <div class="summary-card-trend">
                    <i class="fas fa-arrow-up"></i> In Last 7 days
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-card-title">Recent Responses</div>
                    <div class="summary-card-value"><?php echo countRecentFormResponses($conn); ?></div>
                    <div class="summary-card-trend">
                    <i class="fas fa-arrow-up"></i> In Last 7 days
                    </div>
                </div>
            </div>
            <!-- Search and Filter Bar -->
            <div class="search-filter-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search forms..." id="searchInput">
                </div>
                <div class="filter-options">
                    <select class="filter-select" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="draft">Draft</option>
                        <option value="closed">Closed</option>
                    </select>
                    <select class="filter-select" id="sortSelect">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="popular">Most Responses</option>
                    </select>
                </div>
            </div>

            <!-- Forms Grid -->
            <div class="forms-grid" id="formsGrid">
                <!-- Sample Form Card -->
                 <?php
                 $forms = getAllForms($conn);
                 foreach ($forms as $form):
                 ?>
                 
                <div class="form-card">
                    <div class="form-card-header">
                        <h3 class="form-card-title"><?php echo $form['title'];?> </h3>
                        <span class="status-badge status-active">Active</span>
                    </div>
                    <div class="form-card-body">
                        <div class="form-card-stats">
                            <div class="stat-item">
                                <div class="stat-number"><?php echo countFormResponsesByForm($conn, $form['id']); ?></div>
                                <div class="stat-label">Responses</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">0</div>
                                <div class="stat-label">Views</div>
                            </div>
                        </div>
                        <div class="form-card-meta">
                            <div class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                Created: <?php echo $form['created_at'];?>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                Last updated: <?php echo timeAgo($form['updated_at']); ?>                            </div>
                        </div>
                        <div class="form-card-actions">
                            <a href="preview.php?id=<?php echo $form['id']; ?>" class="btn btn-success">
                                <i class="fas fa-eye"></i> Preview
                            </a>

                            <!-- View Responses: link to responses page -->
                            <a href="form_responses.php?id=<?php echo $form['id']; ?>" class="btn btn-success">
                                <i class="fas fa-chart-bar"></i> View Responses
                            </a>

                            <!-- Edit: link to edit form -->
                            <a href="edit_form.php?id=<?php echo $form['id']; ?>" class="btn btn-success">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>

                            <!-- Delete: action via JS or form -->
                            <button class="btn btn-success delete-btn" data-form-id="<?php echo $form['id']; ?>">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>

            </div>

            <!-- Empty State -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <i class="fas fa-file-alt empty-state-icon"></i>
                <h2 class="empty-state-title">No Forms Found</h2>
                <p>Try adjusting your search or create a new form.</p>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <span class="page-item active">1</span>
                <span class="page-item">2</span>
                <span class="page-item">3</span>
            </div>
        </div>
    </div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Delete Form</h3>
            <button class="close-button">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this form? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-danger" id="confirmDelete">Delete</button>
            <button class="btn btn-primary close-button">Cancel</button>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div id="shareModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Share Form</h3>
            <button class="close-button">&times;</button>
        </div>
        <div class="modal-body">
            <p>Share this form using the link below:</p>
            <div class="search-box" style="margin-top: 10px;">
                <input type="text" id="shareUrl" value="https://forms.ashesi.edu.gh/student-feedback" readonly>
                <i class="fas fa-copy" id="copyBtn" style="cursor: pointer;"></i>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = []; // Replace with actual data from server
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const sortSelect = document.getElementById('sortSelect');
        const formsGrid = document.getElementById('formsGrid');
        const emptyState = document.getElementById('emptyState');

        // Modals
        const modals = document.querySelectorAll('.modal');
        const closeButtons = document.querySelectorAll('.close-button');
        const deleteBtns = document.querySelectorAll('.delete-btn');
        const shareBtns = document.querySelectorAll('.share-btn');

        // Filter forms
        function filterForms() {
            const searchTerm = searchInput.value.toLowerCase();
            const status = statusFilter.value;
            const sortBy = sortSelect.value;

            // Implement filtering and sorting logic here
            // Update formsGrid.innerHTML accordingly
            // Show/hide empty state
        }

        // Event listeners
        searchInput.addEventListener('input', filterForms);
        statusFilter.addEventListener('change', filterForms);
        sortSelect.addEventListener('change', filterForms);

        // Modal handling
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                modals.forEach(modal => modal.style.display = 'none');
            });
        });

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Delete form
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('deleteModal').style.display = 'block';
            });
        });

        // Share form
        shareBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('shareModal').style.display = 'block';
            });
        });

        // Copy URL
        document.getElementById('copyBtn').addEventListener('click', () => {
            const url = document.getElementById('shareUrl');
            url.select();
            document.execCommand('copy');
            // Show confirmation tooltip
        });

        // Initial load
        filterForms();
    });
</script>
</body>
</html>