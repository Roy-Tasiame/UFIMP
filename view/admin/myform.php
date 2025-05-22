<?php
include '../../functions/totalforms.php';
// Assume we have a function to get all forms created by the current user
// This function would query the database and return an array of form data
function getUserForms($conn) {
    // This is a placeholder function
    // In real implementation, this would query the database based on user ID
    
    // Placeholder data for demonstration
    return [
        [
            'id' => 1,
            'title' => 'Student Satisfaction Survey',
            'description' => 'Annual survey to gather feedback on student experience',
            'created_date' => '2025-04-10',
            'submissions' => 24
        ],
        [
            'id' => 2,
            'title' => 'Course Evaluation Form',
            'description' => 'End of semester course evaluation',
            'created_date' => '2025-04-15',
            'submissions' => 45
        ],
        [
            'id' => 3,
            'title' => 'Campus Facilities Feedback',
            'description' => 'Feedback on campus facilities and services',
            'created_date' => '2025-04-20',
            'submissions' => 12
        ],
        [
            'id' => 4,
            'title' => 'Diversity and Inclusion Survey',
            'description' => 'Assessment of diversity and inclusion initiatives',
            'created_date' => '2025-04-25',
            'submissions' => 18
        ]
    ];
}

// Get user forms
$userForms = getUserForms($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Forms - Form Creator</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="myforms.css">
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
                <a href="myforms.php" class="nav-link active">
                    <i class="fas fa-file-alt"></i>
                    My Forms
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    Analytics
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-building"></i>
                    Departments
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-users"></i>
                    Users
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
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
                <h1 class="page-title">My Forms</h1>
                <div class="new-form">
                    <a href="createform.php"><button><i class="fas fa-plus"></i> Create New Form</button></a>
                </div>
            </div>

            <!-- Forms Grid -->
            <div class="forms-grid">
                <?php if (empty($userForms)): ?>
                    <div class="empty-state">
                        <i class="fas fa-file-alt empty-icon"></i>
                        <h3>No Forms Created Yet</h3>
                        <p>Click "Create New Form" to get started with your first form.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($userForms as $form): ?>
                        <div class="form-card">
                            <div class="form-card-header">
                                <div class="form-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="form-status">
                                    <span class="status-badge active">Active</span>
                                </div>
                            </div>
                            <div class="form-card-body">
                                <h3 class="form-title"><?php echo $form['title']; ?></h3>
                                <p class="form-description"><?php echo $form['description']; ?></p>
                                <div class="form-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>Created: <?php echo $form['created_date']; ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span><?php echo $form['submissions']; ?> Submissions</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-card-footer">
                                <a href="viewform.php?id=<?php echo $form['id']; ?>" class="form-action-btn"><i class="fas fa-eye"></i> View Form</a>
                                <a href="submissions.php?id=<?php echo $form['id']; ?>" class="form-action-btn"><i class="fas fa-chart-bar"></i> View Submissions</a>
                                <div class="form-options">
                                    <button class="options-btn"><i class="fas fa-ellipsis-v"></i></button>
                                    <div class="dropdown-menu">
                                        <a href="editform.php?id=<?php echo $form['id']; ?>"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="shareform.php?id=<?php echo $form['id']; ?>"><i class="fas fa-share"></i> Share</a>
                                        <a href="duplicateform.php?id=<?php echo $form['id']; ?>"><i class="fas fa-copy"></i> Duplicate</a>
                                        <a href="#" class="delete-link" data-id="<?php echo $form['id']; ?>"><i class="fas fa-trash"></i> Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        // Toggle dropdown menu for form options
        document.addEventListener('DOMContentLoaded', function() {
            const optionBtns = document.querySelectorAll('.options-btn');
            
            optionBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const dropdown = this.nextElementSibling;
                    dropdown.classList.toggle('show');
                });
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                const dropdowns = document.querySelectorAll('.dropdown-menu');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            });
            
            // Delete confirmation
            const deleteLinks = document.querySelectorAll('.delete-link');
            deleteLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const formId = this.getAttribute('data-id');
                    if(confirm('Are you sure you want to delete this form? This action cannot be undone.')) {
                        // Send delete request (implementation would depend on your system)
                        console.log('Deleting form with ID: ' + formId);
                    }
                });
            });
        });
    </script>
</body>
</html>