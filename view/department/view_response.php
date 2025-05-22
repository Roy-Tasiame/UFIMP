<?php
// Start session and include database connection
session_start();
require_once '../../settings/config.php';

// Check if user is logged in and has appropriate role
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get response ID from URL
$response_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get response details
$query = "SELECT fr.*, f.title as form_title, f.id as form_id, f.user_id as form_owner, u.name as responder_name, u.email as responder_email
          FROM form_responses fr
          JOIN forms f ON fr.form_id = f.id
          LEFT JOIN users u ON fr.user_id = u.user_id
          WHERE fr.id = $response_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    // Response not found
    header('Location: dashboard.php');
    exit();
}

$response = mysqli_fetch_assoc($result);
$form_id = $response['form_id'];

// Check if the current user owns the form
if ($response['form_owner'] != $_SESSION['user_id']) {
    // Not the form owner
    header('Location: dashboard.php');
    exit();
}

// Get form fields
$query = "SELECT * FROM form_fields WHERE form_id = $form_id ORDER BY order_position";
$fields_result = mysqli_query($conn, $query);
$fields = [];
while ($row = mysqli_fetch_assoc($fields_result)) {
    $fields[$row['id']] = $row;
}

// Get response values
$query = "SELECT * FROM response_values WHERE response_id = $response_id";
$values_result = mysqli_query($conn, $query);
$values = [];
while ($row = mysqli_fetch_assoc($values_result)) {
    $values[$row['field_id']] = $row['value'];
}

// Get options for checkbox, radio, and select fields
$field_ids = array_keys($fields);
if (!empty($field_ids)) {
    $field_ids_str = implode(',', $field_ids);
    $query = "SELECT * FROM field_options WHERE field_id IN ($field_ids_str) ORDER BY order_position";
    $options_result = mysqli_query($conn, $query);
    $options = [];
    while ($row = mysqli_fetch_assoc($options_result)) {
        if (!isset($options[$row['field_id']])) {
            $options[$row['field_id']] = [];
        }
        $options[$row['field_id']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Response - <?php echo htmlspecialchars($response['form_title']); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
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

        /* Response Info */
        .response-info {
            background-color: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .response-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .meta-item {
            margin-bottom: 0.5rem;
        }

        .meta-item strong {
            color: var(--gray-dark);
            display: block;
            margin-bottom: 0.25rem;
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

        /* Response Details */
        .response-details {
            background-color: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }

        .response-details h2 {
            color: var(--gray-dark);
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
        }

        .response-item {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .response-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .question {
            margin-bottom: 0.75rem;
        }

        .question strong {
            font-size: 1.1rem;
            color: var(--gray-dark);
            display: block;
            margin-bottom: 0.25rem;
        }

        .field-type {
            display: inline-block;
            background-color: var(--gray);
            color: var(--gray-dark);
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            margin-top: 0.25rem;
        }

        .answer {
            background-color: var(--gray);
            padding: 1rem;
            border-radius: 8px;
        }

        .answer em {
            color: #6c757d;
            font-style: italic;
        }

        .selected-options {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .selected-options li {
            padding: 0.25rem 0;
        }

        .selected-options li:before {
            content: "✓";
            margin-right: 0.5rem;
            color: var(--maroon);
        }

        .rating-display {
            display: flex;
            align-items: center;
        }

        .star {
            font-size: 1.25rem;
            margin-right: 0.25rem;
        }

        .star.filled {
            color: #ffc107;
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
                <h1 class="page-title">Response Details</h1>
                <div class="actions">
                    <a href="form_responses.php?id=<?php echo $form_id; ?>" class="btn">
                        <i class="fas fa-arrow-left"></i> Back to Responses
                    </a>
                    <a href="export_single_response.php?id=<?php echo $response_id; ?>" class="btn">
                        <i class="fas fa-download"></i> Export Response
                    </a>
                </div>
            </div>
            
            <div class="response-info">
                <div class="response-meta">
                    <div class="meta-item">
                        <strong>Form</strong> 
                        <?php echo htmlspecialchars($response['form_title']); ?>
                    </div>
                    <div class="meta-item">
                        <strong>Response ID</strong> 
                        <?php echo $response_id; ?>
                    </div>
                    <div class="meta-item">
                        <strong>Submitted</strong> 
                        <?php echo date('M d, Y H:i', strtotime($response['created_at'])); ?>
                    </div>
                    <div class="meta-item">
                        <strong>Status</strong> 
                        <span class="status-badge <?php echo $response['is_complete'] ? 'complete' : 'incomplete'; ?>">
                            <?php echo $response['is_complete'] ? 'Complete' : 'Incomplete'; ?>
                        </span>
                    </div>
                    <div class="meta-item">
                        <strong>Responder</strong> 
                        <?php 
                        if ($response['user_id'] && $response['responder_name']) {
                            echo htmlspecialchars($response['responder_name']) . ' (' . htmlspecialchars($response['responder_email']) . ')';
                        } else {
                            echo 'Anonymous';
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="response-details">
                <h2><i class="fas fa-clipboard-list"></i> Responses</h2>
                
                <?php if (empty($fields)): ?>
                    <div class="no-data">
                        <p>No form fields found for this response.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($fields as $field): ?>
                        <div class="response-item">
                            <div class="question">
                                <strong><?php echo htmlspecialchars($field['label']); ?></strong>
                                <span class="field-type"><?php echo $field['type']; ?></span>
                            </div>
                            
                            <div class="answer">
                                <?php
                                $field_id = $field['id'];
                                $value = isset($values[$field_id]) ? $values[$field_id] : '';
                                
                                switch ($field['type']) {
                                    case 'checkbox':
                                        // Display selected options
                                        if (!empty($value)) {
                                            $selected_options = explode(', ', $value);
                                            echo '<ul class="selected-options">';
                                            foreach ($selected_options as $option) {
                                                echo '<li>' . htmlspecialchars($option) . '</li>';
                                            }
                                            echo '</ul>';
                                        } else {
                                            echo '<em>No selection</em>';
                                        }
                                        break;
                                        
                                    case 'radio':
                                    case 'select':
                                        echo !empty($value) ? htmlspecialchars($value) : '<em>No selection</em>';
                                        break;
                                        
                                    case 'textarea':
                                        echo !empty($value) ? nl2br(htmlspecialchars($value)) : '<em>No response</em>';
                                        break;
                                        
                                    case 'file':
                                        if (!empty($value)) {
                                            echo '<a href="uploads/' . htmlspecialchars($value) . '" target="_blank" class="btn">
                                                <i class="fas fa-download"></i> Download File
                                            </a>';
                                        } else {
                                            echo '<em>No file uploaded</em>';
                                        }
                                        break;
                                        
                                    case 'rating':
                                        if (!empty($value)) {
                                            echo '<div class="rating-display">';
                                            $rating = intval($value);
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rating) {
                                                    echo '<span class="star filled">★</span>';
                                                } else {
                                                    echo '<span class="star">☆</span>';
                                                }
                                            }
                                            echo ' <span class="rating-text">(' . $rating . '/5)</span></div>';
                                        } else {
                                            echo '<em>No rating given</em>';
                                        }
                                        break;
                                        
                                    default:
                                        echo !empty($value) ? htmlspecialchars($value) : '<em>No response</em>';
                                        break;
                                }
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>