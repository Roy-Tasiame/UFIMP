<?php
    session_start();
    $name = $_SESSION['user_name'];
    $user_id = $_SESSION['user_id'];
    include '../../functions/total_users.php';
    include '../../functions/student_functions.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ashesi Feedback Portal - Feedback History</title>
    <style>
        :root {
            --maroon: #800000;
            --black: #000000;
            --white: #ffffff;
            --gray: #f5f5f5;
            --light-gray: #e0e0e0;
            --medium-gray: #c0c0c0;
            --dark-gray: #666666;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: var(--gray);
            background-image: url('../assets/img/bg.png'); 
            background-size: cover;
            background-position: center; 
            background-repeat: no-repeat;
            min-height: 100vh;
        }

        header {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .logo img {
            height: 50px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-name {
            margin-right: 1rem;
            font-weight: bold;
        }

        .logout-btn {
            padding: 0.5rem 1rem;
            background-color: var(--maroon);
            color: var(--white);
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Top Navigation Bar */
        .top-nav {
            background-color: var(--maroon);
            padding: 0.5rem 0;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .nav-link {
            color: var(--white);
            text-decoration: none;
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: bold;
        }

        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .page-title {
            margin-bottom: 1.5rem;
            color: var(--maroon);
        }

        .feedback-tools {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
        }

        .search-bar {
            flex: 1;
            min-width: 300px;
            display: flex;
        }

        .search-bar input {
            flex: 1;
            padding: 0.6rem 1rem;
            border: 1px solid var(--light-gray);
            border-radius: 5px 0 0 5px;
            font-size: 0.9rem;
        }

        .search-bar button {
            padding: 0.6rem 1rem;
            background-color: var(--maroon);
            color: var(--white);
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }

        .filter-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .filter-select {
            padding: 0.6rem;
            border: 1px solid var(--light-gray);
            border-radius: 5px;
            font-size: 0.9rem;
            min-width: 150px;
        }

        .display-controls {
            display: flex;
            gap: 0.8rem;
        }

        .view-btn {
            padding: 0.5rem;
            background-color: var(--light-gray);
            color: var(--dark-gray);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .view-btn.active {
            background-color: var(--maroon);
            color: var(--white);
        }

        /* Table View */
        .feedback-table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .feedback-table th {
            background-color: var(--maroon);
            color: var(--white);
            padding: 1rem;
            text-align: left;
        }

        .feedback-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--light-gray);
        }

        .feedback-table tr:last-child td {
            border-bottom: none;
        }

        .feedback-table tr:hover {
            background-color: var(--gray);
        }

        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status-submitted {
            background-color: #e6f7ff;
            color: #0066cc;
        }

        .status-reviewed {
            background-color: #fff2e6;
            color: #ff6600;
        }

        .status-implemented {
            background-color: #e6f9e6;
            color: #008800;
        }

        .status-archived {
            background-color: #f2f2f2;
            color: #666666;
        }

        .action-btn {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            margin-right: 0.5rem;
        }

        .btn-view {
            background-color: var(--light-gray);
            color: var(--black);
        }

        .btn-download {
            background-color: var(--maroon);
            color: var(--white);
        }

        .feedback-item-score {
            font-weight: bold;
        }

        .score-high {
            color: #008800;
        }

        .score-medium {
            color: #ff6600;
        }

        .score-low {
            color: #cc0000;
        }

        .paginator {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            gap: 0.5rem;
        }

        .page-item {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--white);
            border: 1px solid var(--light-gray);
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .page-item:hover {
            background-color: var(--light-gray);
        }

        .page-item.active {
            background-color: var(--maroon);
            color: var(--white);
            border-color: var(--maroon);
        }

        /* Summary Card */
        .feedback-summary {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .summary-card {
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            background-color: var(--gray);
        }

        .summary-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--maroon);
            margin-bottom: 0.5rem;
        }

        .summary-label {
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                padding: 1rem;
            }

            .logo img {
                margin-bottom: 1rem;
            }

            .nav-container {
                padding: 0 1rem;
                overflow-x: auto;
                justify-content: flex-start;
            }

            .main-container {
                padding: 0 1rem;
            }

            .feedback-tools {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-controls {
                flex-direction: column;
            }

            .feedback-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <img src="../../assets/img/ashesilogo.png" alt="Ashesi University Logo">
            </div>
            <div class="user-info">
                <div class="user-name">Welcome, <strong><?php echo $name; ?></strong></div>
                <a href = "../../actions/logout.php" style = "text-decoration: none;"><button class="logout-btn">Logout</button></a>
            </div>
        </div>
    </header>

    <!-- Top Navigation Bar -->
    <nav class="top-nav">
        <div class="nav-container">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="history.php" class="nav-link active">History/Analytics</a>
            <a href="profile.php" class="nav-link">Profile</a>
            <a href="help.php" class="nav-link">Help & Support</a>
        </div>
    </nav>

    <div class="main-container">
        <h1 class="page-title">Your Feedback History</h1>

        <!-- Feedback Summary -->
        <div class="feedback-summary">
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-number"><?php echo countAllResponsesByStudent($conn, $user_id); ?></div>
                    <div class="summary-label">Total Submissions</div>
                </div>
                <div class="summary-card">
                    <div class="summary-number"><?php echo countRecentResponsesByStudent($conn, $user_id, 1); ?></div>
                    <div class="summary-label">Recent Submissions</div>
                </div>
                <div class="summary-card">
                    <div class="summary-number"><?php echo getFormsNotYetFilled($conn, $user_id); ?></div>
                    <div class="summary-label">Forms Yet to be Filled</div>
                </div>
                <div class="summary-card">
                    <div class="summary-number"><?php echo getIncompleteResponses($conn, $user_id); ?></div>
                    <div class="summary-label">Incomplete Forms</div>
                </div>
            </div>
        </div>

        <!-- Filter and Search Tools -->
        <div class="feedback-tools">
            <div class="search-bar">
                <input type="text" placeholder="Search by keyword or form title...">
                <button>Search</button>
            </div>
            <div class="filter-controls">
                <select class="filter-select">
                    <option value="">All Departments</option>
                    <?php $departments = getAllDepartments($conn); 
                    foreach ($departments as $department):
                    ?>
                    <option value="academic"><?php echo $department['name']; ?></option>
                    <?php endforeach; ?>

                </select>
                <select class="filter-select">
                    <option value="">All Status</option>
                    <option value="submitted">Complete</option>
                    <option value="reviewed">Incomplete</option>
                </select>
            </div>
            <div class="display-controls">
                <button class="view-btn active" title="Table View">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <rect x="1" y="1" width="14" height="3" rx="1"/>
                        <rect x="1" y="6" width="14" height="3" rx="1"/>
                        <rect x="1" y="11" width="14" height="3" rx="1"/>
                    </svg>
                </button>
                <button class="view-btn" title="Card View">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <rect x="1" y="1" width="6" height="6" rx="1"/>
                        <rect x="9" y="1" width="6" height="6" rx="1"/>
                        <rect x="1" y="9" width="6" height="6" rx="1"/>
                        <rect x="9" y="9" width="6" height="6" rx="1"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Feedback History Table -->
         <?php $responses = getFormResponseHistory($conn, $user_id); 
         if ($responses && count($responses) > 0) {
            echo "<table class='feedback-table'>
                <thead>
                    <tr>
                        <th>Form Title</th>
                        <th>Department</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";
                foreach ($responses as $response) {
                    echo "<tr>
                        <td><a href='view_form_response.php?response_id={$response['form_response_id']}' style = 'text-decoration: none; color: black;'>{$response['form_title']}</a></td>
                        <td>{$response['department']}</td>
                        <td>{$response['date_submitted']}</td>
                        <td><span class='status-badge status-implemented'>{$response['status']}</span></td>
                        <td>
                            <button class='action-btn btn-view'><a href='view_form_response.php?response_id={$response['form_response_id']}' style = 'text-decoration: none; color: black;'>View</a></button>
                            <button class='action-btn btn-download'><a href='edit_form_response.php?response_id={$response['form_response_id']}' style = 'text-decoration: none; color: black;'>Edit</a></button>
                        </td>
                    </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "No form response history found.";
            }
            ?>

        <!-- Pagination -->
        <div class="paginator">
            <div class="page-item">&laquo;</div>
            <div class="page-item active">1</div>
            <div class="page-item">2</div>
            <div class="page-item">3</div>
            <div class="page-item">4</div>
            <div class="page-item">&raquo;</div>
        </div>
    </div>

    <script>
        // Toggle between view modes
        const viewButtons = document.querySelectorAll('.view-btn');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                viewButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                // Additional code would go here to actually change the view
            });
        });

        // Pagination functionality
        const pageItems = document.querySelectorAll('.page-item');
        pageItems.forEach(item => {
            item.addEventListener('click', function() {
                if (!this.classList.contains('active') && this.textContent !== '«' && this.textContent !== '»') {
                    pageItems.forEach(pg => pg.classList.remove('active'));
                    this.classList.add('active');
                    // Additional code would go here to fetch and display the relevant page of results
                }
            });
        });

        // Filter functionality
        document.querySelectorAll('.filter-select').forEach(select => {
            select.addEventListener('change', function() {
                // This would trigger a re-fetch of data with the applied filters
                console.log('Filter changed:', this.value);
            });
        });

        // Search functionality
        document.querySelector('.search-bar button').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-bar input').value;
            // This would trigger a search with the entered term
            console.log('Searching for:', searchTerm);
        });
    </script>
</body>
</html>