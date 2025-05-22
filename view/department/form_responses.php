<?php
// Start session and include database connection
session_start();
require_once '../../settings/config.php';

// Check if user is logged in and has appropriate role
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get form ID from URL
$form_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Make sure form exists and user has permission to view it
$query = "SELECT * FROM forms WHERE id = $form_id AND user_id = " . $_SESSION['user_id'];
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    // Form not found or user doesn't have permission
    header('Location: dashboard.php');
    exit();
}

$form = mysqli_fetch_assoc($result);

// Get all responses for this form
$query = "
    SELECT fr.*, u.name as responder_name, u.email as responder_email
    FROM form_responses fr
    LEFT JOIN users u ON fr.user_id = u.user_id
    WHERE fr.form_id = $form_id
    ORDER BY fr.created_at DESC
";
$response_result = mysqli_query($conn, $query);
$responses = [];
while ($row = mysqli_fetch_assoc($response_result)) {
    $responses[] = $row;
}

// Get form fields
$query = "
    SELECT * FROM form_fields
    WHERE form_id = $form_id
    ORDER BY order_position
";
$fields_result = mysqli_query($conn, $query);
$fields = [];
while ($row = mysqli_fetch_assoc($fields_result)) {
    $fields[] = $row;
}

// Get response count
$responseCount = count($responses);

// Get complete vs incomplete counts
$completeCount = 0;
$incompleteCount = 0;
foreach ($responses as $response) {
    if ($response['is_complete'] == 1) {
        $completeCount++;
    } else {
        $incompleteCount++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Responses - <?php echo htmlspecialchars($form['title']); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --maroon: #800000;
            --maroon-light: #a06666;
            --black: #000000;
            --white: #ffffff;
            --gray: #f8f9fa;
            --gray-dark: #343a40;
            --border: #dee2e6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background-color: var(--gray);
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            background-color: var(--maroon);
            color: var(--white);
            padding: 1.5rem;
            position: fixed;
            width: 250px;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1.5rem;
        }

        .sidebar-logo {
            width: 150px;
            height: auto;
            display: block;
            margin: 0 auto 1rem;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            color: var(--white);
            text-decoration: none;
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link i {
            margin-right: 0.8rem;
            width: 20px;
            text-align: center;
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Main Content Styles */
        .dashboard {
            margin-left: 250px;
            min-height: 100vh;
        }
        
        .main-content {
            padding: 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.8rem;
            color: var(--gray-dark);
        }

        .actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 10px 20px;
            background-color: var(--maroon);
            color: var(--white);
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn:hover {
            background-color: var(--maroon-light);
            transform: scale(1.05);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 14px;
        }

        /* Analytics Grid */
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Stats Cards */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background-color: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .card-value {
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--maroon);
            margin-bottom: 0.5rem;
        }

        .card-label {
            font-size: 1.1rem;
            color: var(--gray-dark);
        }

        /* Responses Table */
        .responses-table {
            background-color: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: var(--gray);
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            font-weight: 600;
            color: var(--gray-dark);
        }

        tbody tr:hover {
            background-color: var(--gray);
        }

        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.65rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.complete {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-badge.incomplete {
            background-color: #f8d7da;
            color: #842029;
        }

        .no-data {
            background-color: var(--white);
            border-radius: 12px;
            padding: 3rem 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .no-data p {
            color: var(--gray-dark);
            font-size: 1.1rem;
        }
    </style>
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
                <h1 class="page-title">Responses: <?php echo htmlspecialchars($form['title']); ?></h1>
                <div class="actions">
                    <a href="form_analytics.php?id=<?php echo $form_id; ?>" class="btn">
                        <i class="fas fa-chart-bar"></i> View Analytics
                    </a>
                    <a href="export_responses.php?id=<?php echo $form_id; ?>" class="btn">
                        <i class="fas fa-download"></i> Export CSV
                    </a>
                </div>
            </div>
            
            <div class="stats-cards">
                <div class="card">
                    <div class="card-value"><?php echo $responseCount; ?></div>
                    <div class="card-label">Total Responses</div>
                </div>
                <div class="card">
                    <div class="card-value"><?php echo $completeCount; ?></div>
                    <div class="card-label">Complete</div>
                </div>
                <div class="card">
                    <div class="card-value"><?php echo $incompleteCount; ?></div>
                    <div class="card-label">Incomplete</div>
                </div>
            </div>
            
            <?php if (count($responses) > 0): ?>
                <div class="responses-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Responder</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($responses as $response): ?>
                                <tr>
                                    <td><?php echo $response['id']; ?></td>
                                    <td>
                                        <?php 
                                        if ($response['user_id'] && $response['responder_name']) {
                                            echo htmlspecialchars($response['responder_name']) . '<br>';
                                            echo '<small>' . htmlspecialchars($response['responder_email']) . '</small>';
                                        } else {
                                            echo 'Anonymous';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo date('M d, Y H:i', strtotime($response['created_at'])); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $response['is_complete'] ? 'complete' : 'incomplete'; ?>">
                                            <?php echo $response['is_complete'] ? 'Complete' : 'Incomplete'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="view_response.php?id=<?php echo $response['id']; ?>" class="btn btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <p>No responses have been submitted for this form yet.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script src="../../assets/js/responses.js"></script>
</body>
</html>