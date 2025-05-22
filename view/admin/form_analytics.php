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

// Get form fields
$query = "SELECT * FROM form_fields WHERE form_id = $form_id ORDER BY order_position";
$fields_result = mysqli_query($conn, $query);
$fields = [];
while ($row = mysqli_fetch_assoc($fields_result)) {
    $fields[] = $row;
}

// Get response data
$query = "SELECT * FROM form_responses WHERE form_id = $form_id";
$responses_result = mysqli_query($conn, $query);
$responses = [];
while ($row = mysqli_fetch_assoc($responses_result)) {
    $responses[] = $row;
}

// Get total form views
$query = "SELECT COUNT(*) as total_views FROM form_views WHERE form_id = $form_id";
$views_result = mysqli_query($conn, $query);
$views_data = mysqli_fetch_assoc($views_result);
$total_views = $views_data ? $views_data['total_views'] : 0;

// Calculate total responses
$total_responses = count($responses);

// Calculate completion rate
$query = "SELECT COUNT(*) as complete_count FROM form_responses WHERE form_id = $form_id AND is_complete = 1";
$complete_result = mysqli_query($conn, $query);
$complete_data = mysqli_fetch_assoc($complete_result);
$complete_count = $complete_data ? $complete_data['complete_count'] : 0;

$completion_rate = $total_responses > 0 ? round(($complete_count / $total_responses) * 100) : 0;

// Get response data for charts
$query = "SELECT DATE(created_at) as response_date, COUNT(*) as count 
          FROM form_responses 
          WHERE form_id = $form_id 
          GROUP BY DATE(created_at) 
          ORDER BY response_date";
$daily_result = mysqli_query($conn, $query);
$daily_responses = [];
while ($row = mysqli_fetch_assoc($daily_result)) {
    $daily_responses[] = $row;
}

// Get field-specific analytics for each field type
$field_analytics = [];
foreach ($fields as $field) {
    $field_id = $field['id'];
    $field_type = $field['type'];
    
    // Different analytics based on field type
    switch ($field_type) {
        case 'checkbox':
        case 'radio':
        case 'select':
            // Get options for this field
            $query = "SELECT * FROM field_options WHERE field_id = $field_id ORDER BY order_position";
            $options_result = mysqli_query($conn, $query);
            $options = [];
            while ($row = mysqli_fetch_assoc($options_result)) {
                $options[$row['option_text']] = 0; // Initialize counts
            }
            
            // Count responses for each option
            $query = "SELECT value FROM response_values WHERE field_id = $field_id";
            $values_result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($values_result)) {
                if ($field_type == 'checkbox') {
                    // Multiple selections possible
                    $selected = explode(', ', $row['value']);
                    foreach ($selected as $option) {
                        if (isset($options[$option])) {
                            $options[$option]++;
                        }
                    }
                } else {
                    // Single selection
                    if (isset($options[$row['value']])) {
                        $options[$row['value']]++;
                    }
                }
            }
            
            $field_analytics[$field_id] = [
                'type' => $field_type,
                'label' => $field['label'],
                'data' => $options
            ];
            break;
            
        case 'number':
        case 'rating':
            // Calculate average, min, max
            $query = "SELECT AVG(CAST(value AS DECIMAL(10,2))) as avg_value, 
                     MIN(CAST(value AS DECIMAL(10,2))) as min_value, 
                     MAX(CAST(value AS DECIMAL(10,2))) as max_value 
                     FROM response_values 
                     WHERE field_id = $field_id AND value != ''";
            $stats_result = mysqli_query($conn, $query);
            $stats = mysqli_fetch_assoc($stats_result);
            
            // Get distribution of values
            $query = "SELECT value, COUNT(*) as count 
                     FROM response_values 
                     WHERE field_id = $field_id AND value != '' 
                     GROUP BY value 
                     ORDER BY CAST(value AS DECIMAL(10,2))";
            $dist_result = mysqli_query($conn, $query);
            $distribution = [];
            while ($row = mysqli_fetch_assoc($dist_result)) {
                $distribution[$row['value']] = $row['count'];
            }
            
            $field_analytics[$field_id] = [
                'type' => $field_type,
                'label' => $field['label'],
                'stats' => $stats,
                'distribution' => $distribution
            ];
            break;
            
        case 'text':
        case 'textarea':
            // Count responses and response length stats
            $query = "SELECT 
                     COUNT(*) as response_count,
                     AVG(LENGTH(value)) as avg_length,
                     MAX(LENGTH(value)) as max_length
                     FROM response_values 
                     WHERE field_id = $field_id AND value != ''";
            $text_result = mysqli_query($conn, $query);
            $text_stats = mysqli_fetch_assoc($text_result);
            
            $field_analytics[$field_id] = [
                'type' => $field_type,
                'label' => $field['label'],
                'stats' => $text_stats
            ];
            break;
            
        default:
            // Basic response count
            $query = "SELECT COUNT(*) as response_count 
                     FROM response_values 
                     WHERE field_id = $field_id AND value != ''";
            $count_result = mysqli_query($conn, $query);
            $count_data = mysqli_fetch_assoc($count_result);
            
            $field_analytics[$field_id] = [
                'type' => $field_type,
                'label' => $field['label'],
                'response_count' => $count_data['response_count']
            ];
            break;
    }
}

// Calculate average time to complete
$query = "SELECT AVG(TIMESTAMPDIFF(SECOND, created_at, completed_at)) as avg_time 
        FROM form_responses 
        WHERE form_id = $form_id AND is_complete = 1";
$time_result = mysqli_query($conn, $query);
$time_data = mysqli_fetch_assoc($time_result);
$avg_seconds = $time_data['avg_time'] ? round($time_data['avg_time']) : 0;
$avg_minutes = floor($avg_seconds / 60);
$avg_seconds_remainder = $avg_seconds % 60;

// Get time distribution
$query = "SELECT 
          CASE 
              WHEN TIMESTAMPDIFF(SECOND, created_at, completed_at) < 60 THEN 'Less than 1 minute'
              WHEN TIMESTAMPDIFF(SECOND, created_at, completed_at) < 300 THEN '1-5 minutes'
              WHEN TIMESTAMPDIFF(SECOND, created_at, completed_at) < 600 THEN '5-10 minutes'
              ELSE 'More than 10 minutes'
          END as time_range,
          COUNT(*) as count
        FROM form_responses 
        WHERE form_id = $form_id AND is_complete = 1
        GROUP BY time_range
        ORDER BY 
          CASE time_range
              WHEN 'Less than 1 minute' THEN 1
              WHEN '1-5 minutes' THEN 2
              WHEN '5-10 minutes' THEN 3
              ELSE 4
          END";
$distribution_result = mysqli_query($conn, $query);
$time_distribution = [];
while ($row = mysqli_fetch_assoc($distribution_result)) {
    $time_distribution[$row['time_range']] = $row['count'];
}

// Prepare data for charts (JSON encoded)
$daily_chart_data = json_encode($daily_responses);
$completion_chart_data = json_encode([
    ['status' => 'Complete', 'count' => $complete_count],
    ['status' => 'Incomplete', 'count' => $total_responses - $complete_count]
]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Analytics - <?php echo htmlspecialchars($form['title']); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Additional styles specific to analytics page */
        .chart-container {
            height: 300px;
            margin-bottom: 2rem;
        }
        
        .field-chart {
            height: 250px;
        }
        
        .field-analytics-item {
            background-color: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .field-analytics-item h3 {
            margin-bottom: 1rem;
            color: var(--gray-dark);
            font-size: 1.2rem;
        }
        
        .field-type {
            color: var(--maroon);
            font-size: 0.9rem;
            font-weight: normal;
        }
        
        .stats-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .stat-item {
            background-color: var(--gray);
            padding: 0.8rem 1rem;
            border-radius: 8px;
            min-width: 150px;
        }
        
        .stat-label {
            color: var(--gray-dark);
            font-size: 0.9rem;
            display: block;
            margin-bottom: 0.3rem;
        }
        
        .stat-value {
            color: var(--maroon);
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background-color: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }
        
        .card-value {
            font-size: 2rem;
            font-weight: 600;
            color: var(--maroon);
            margin-bottom: 0.5rem;
        }
        
        .card-label {
            color: var(--gray-dark);
            font-size: 1rem;
        }
        
        .analytics-overview, .field-analytics, .response-time-analysis {
            margin-bottom: 2rem;
        }
        
        .actions {
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            background-color: var(--maroon);
            color: var(--white);
            padding: 0.7rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            font-weight: 500;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background-color: var(--maroon-light);
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
                <h1 class="page-title">Analytics: <?php echo htmlspecialchars($form['title']); ?></h1>
                <div class="actions">
                    <a href="form_responses.php?id=<?php echo $form_id; ?>" class="btn">
                        <i class="fas fa-list-ul"></i> View Responses
                    </a>
                    <a href="export_analytics.php?id=<?php echo $form_id; ?>" class="btn">
                        <i class="fas fa-download"></i> Export Analytics
                    </a>
                </div>
            </div>
            
            <div class="analytics-overview">
                <div class="analytics-grid">
                    <div class="analytics-card">
                        <div class="card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="card-content">
                            <h3>Total Responses</h3>
                            <p class="card-value"><?php echo $total_responses; ?></p>
                        </div>
                    </div>
                    
                    <div class="analytics-card">
                        <div class="card-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="card-content">
                            <h3>Form Views</h3>
                            <p class="card-value"><?php echo $total_views; ?></p>
                        </div>
                    </div>
                    
                    <div class="analytics-card">
                        <div class="card-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-content">
                            <h3>Completion Rate</h3>
                            <p class="card-value"><?php echo $completion_rate; ?>%</p>
                        </div>
                    </div>
                    
                    <div class="analytics-card">
                        <div class="card-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="card-content">
                            <h3>Conversion Rate</h3>
                            <p class="card-value"><?php echo $total_views > 0 ? round(($total_responses / $total_views) * 100) : 0; ?>%</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Daily Response Chart -->
            <div class="chart-card">
                <h3>Daily Response Trend</h3>
                <div class="chart-container">
                    <canvas id="dailyResponseChart"></canvas>
                </div>
            </div>
            
            <div class="field-analytics">
                <h2>Field-Specific Analytics</h2>
                
                <?php foreach ($fields as $field): ?>
                    <?php 
                    $field_id = $field['id'];
                    if (!isset($field_analytics[$field_id])) continue;
                    $analytics = $field_analytics[$field_id];
                    ?>
                    
                    <div class="field-analytics-item">
                        <h3><?php echo htmlspecialchars($field['label']); ?> <span class="field-type">(<?php echo $field['type']; ?>)</span></h3>
                        
                        <?php if (in_array($field['type'], ['checkbox', 'radio', 'select'])): ?>
                            <div class="chart-container field-chart">
                                <canvas id="fieldChart_<?php echo $field_id; ?>"></canvas>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const ctx = document.getElementById('fieldChart_<?php echo $field_id; ?>').getContext('2d');
                                    const chartData = {
                                        labels: [<?php 
                                            $labels = array_keys($analytics['data']);
                                            echo "'" . implode("', '", array_map('addslashes', $labels)) . "'";
                                        ?>],
                                        datasets: [{
                                            label: 'Responses',
                                            data: [<?php echo implode(', ', array_values($analytics['data'])); ?>],
                                            backgroundColor: [
                                                'rgba(128, 0, 0, 0.7)',
                                                'rgba(160, 102, 102, 0.7)',
                                                'rgba(192, 128, 128, 0.7)',
                                                'rgba(224, 153, 153, 0.7)',
                                                'rgba(240, 179, 179, 0.7)',
                                                'rgba(255, 204, 204, 0.7)'
                                            ],
                                            borderColor: 'rgba(128, 0, 0, 1)',
                                            borderWidth: 1
                                        }]
                                    };
                                    
                                    new Chart(ctx, {
                                        type: '<?php echo $field['type'] == 'checkbox' ? 'bar' : 'pie'; ?>',
                                        data: chartData,
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    position: 'right',
                                                }
                                            }
                                        }
                                    });
                                });
                            </script>
                        <?php elseif (in_array($field['type'], ['number', 'rating'])): ?>
                            <div class="stats-summary">
                                <div class="stat-item">
                                    <span class="stat-label">Average:</span>
                                    <span class="stat-value"><?php echo isset($analytics['stats']['avg_value']) ? round($analytics['stats']['avg_value'], 2) : 'N/A'; ?></span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Minimum:</span>
                                    <span class="stat-value"><?php echo isset($analytics['stats']['min_value']) ? $analytics['stats']['min_value'] : 'N/A'; ?></span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Maximum:</span>
                                    <span class="stat-value"><?php echo isset($analytics['stats']['max_value']) ? $analytics['stats']['max_value'] : 'N/A'; ?></span>
                                </div>
                            </div>
                            
                            <?php if (!empty($analytics['distribution'])): ?>
                                <div class="chart-container field-chart">
                                    <canvas id="fieldChart_<?php echo $field_id; ?>"></canvas>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const ctx = document.getElementById('fieldChart_<?php echo $field_id; ?>').getContext('2d');
                                        const chartData = {
                                            labels: [<?php 
                                                $labels = array_keys($analytics['distribution']);
                                                echo "'" . implode("', '", array_map('addslashes', $labels)) . "'";
                                            ?>],
                                            datasets: [{
                                                label: 'Responses',
                                                data: [<?php echo implode(', ', array_values($analytics['distribution'])); ?>],
                                                backgroundColor: 'rgba(128, 0, 0, 0.7)',
                                                borderColor: 'rgba(128, 0, 0, 1)',
                                                borderWidth: 1
                                            }]
                                        };
                                        
                                        new Chart(ctx, {
                                            type: 'bar',
                                            data: chartData,
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                scales: {
                                                    y: {
                                                        beginAtZero: true,
                                                        ticks: {
                                                            precision: 0
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    });
                                </script>
                            <?php endif; ?>
                        <?php elseif (in_array($field['type'], ['text', 'textarea'])): ?>
                            <div class="stats-summary">
                                <div class="stat-item">
                                    <span class="stat-label">Response Count:</span>
                                    <span class="stat-value"><?php echo $analytics['stats']['response_count']; ?></span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Average Length:</span>
                                    <span class="stat-value"><?php echo round($analytics['stats']['avg_length']); ?> characters</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Max Length:</span>
                                    <span class="stat-value"><?php echo $analytics['stats']['max_length']; ?> characters</span>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="stats-summary">
                                <div class="stat-item">
                                    <span class="stat-label">Response Count:</span>
                                    <span class="stat-value"><?php echo $analytics['response_count']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($responses) > 0): ?>
                <div class="response-time-analysis">
                    <div class="chart-card">
                        <h3>Response Time Analysis</h3>
                        <div class="chart-container">
                            <canvas id="responseTimeChart"></canvas>
                        </div>
                        
                        <div class="stats-summary">
                            <div class="stat-item">
                                <span class="stat-label">Average Completion Time:</span>
                                <span class="stat-value"><?php echo $avg_minutes; ?> min <?php echo $avg_seconds_remainder; ?> sec</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
        </main>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Daily Response Chart
            if (document.getElementById('dailyResponseChart')) {
                const dailyData = <?php echo !empty($daily_responses) ? json_encode($daily_responses) : '[]'; ?>;
                const dailyCtx = document.getElementById('dailyResponseChart').getContext('2d');
                
                if (dailyData.length > 0) {
                    const labels = dailyData.map(item => item.response_date);
                    const counts = dailyData.map(item => item.count);
                    
                    new Chart(dailyCtx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Daily Responses',
                                data: counts,
                                backgroundColor: 'rgba(128, 0, 0, 0.2)',
                                borderColor: 'rgba(128, 0, 0, 1)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true,
                                pointBackgroundColor: 'rgba(128, 0, 0, 1)',
                                pointRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                } else {
                    const noDataMessage = document.createElement('div');
                    noDataMessage.innerText = 'No response data available yet.';
                    noDataMessage.style.textAlign = 'center';
                    noDataMessage.style.padding = '100px 0';
                    noDataMessage.style.color = '#666';
                    dailyCtx.canvas.parentNode.replaceChild(noDataMessage, dailyCtx.canvas);
                }
            }
            
            // Response Time Chart
            if (document.getElementById('responseTimeChart')) {
                const timeCtx = document.getElementById('responseTimeChart').getContext('2d');
                const timeData = {
                    labels: [<?php 
                        $time_labels = array_keys($time_distribution);
                        echo !empty($time_labels) ? "'" . implode("', '", array_map('addslashes', $time_labels)) . "'" : "";
                    ?>],
                    datasets: [{
                        label: 'Number of Responses',
                        data: [<?php echo !empty($time_distribution) ? implode(', ', array_values($time_distribution)) : ""; ?>],
                        backgroundColor: 'rgba(128, 0, 0, 0.7)',
                        borderColor: 'rgba(128, 0, 0, 1)',
                        borderWidth: 1
                    }]
                };
                
                if (timeData.labels.length > 0) {
                    new Chart(timeCtx, {
                        type: 'bar',
                        data: timeData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                } else {
                    const noDataMessage = document.createElement('div');
                    noDataMessage.innerText = 'No completion time data available yet.';
                    noDataMessage.style.textAlign = 'center';
                    noDataMessage.style.padding = '100px 0';
                    noDataMessage.style.color = '#666';
                    timeCtx.canvas.parentNode.replaceChild(noDataMessage, timeCtx.canvas);
                }
            }
        });
    </script>
</body>
</html>