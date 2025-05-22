<?php
    session_start();
    $name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ashesi Feedback Portal - Analytics</title>
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

        /* Filter Tools */
        .analytics-tools {
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

        .export-btn {
            padding: 0.6rem 1.2rem;
            background-color: var(--maroon);
            color: var(--white);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
        }

        /* Analytics Cards */
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .analytics-card {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            height: 300px;
            display: flex;
            flex-direction: column;
        }

        .analytics-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: var(--maroon);
        }

        .card-options {
            color: var(--dark-gray);
            cursor: pointer;
        }

        .chart-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Analytics Full Width Cards */
        .analytics-full {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .analytics-full-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .full-chart-container {
            height: 300px;
            position: relative;
        }

        /* Insights Section */
        .insights-container {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .insights-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--maroon);
            margin-bottom: 1rem;
        }

        .insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .insight-card {
            background-color: var(--gray);
            border-radius: 8px;
            padding: 1.2rem;
            border-left: 4px solid var(--maroon);
        }

        .insight-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--maroon);
            margin-bottom: 0.5rem;
        }

        .insight-label {
            font-size: 0.9rem;
            color: var(--dark-gray);
        }

        .insight-trend {
            display: flex;
            align-items: center;
            margin-top: 0.5rem;
            font-size: 0.8rem;
        }

        .trend-up {
            color: #008800;
        }

        .trend-down {
            color: #cc0000;
        }

        .trend-neutral {
            color: var(--dark-gray);
        }

        /* Donut Chart */
        .donut-chart {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .donut-hole {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .donut-ring {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 20px solid var(--light-gray);
            border-top-color: var(--maroon);
            border-right-color: #ff6600;
            border-bottom-color: #0066cc;
            border-left-color: #008800;
        }

        /* Bar Chart */
        .bar-chart {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            padding-bottom: 30px;
            position: relative;
        }

        .bar {
            width: 30px;
            background-color: var(--maroon);
            border-radius: 4px 4px 0 0;
            position: relative;
        }

        .bar-label {
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.8rem;
            color: var(--dark-gray);
        }

        .bar-value {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.8rem;
            color: var(--dark-gray);
        }

        /* Line Chart */
        .line-chart {
            width: 100%;
            height: 100%;
            position: relative;
            padding-bottom: 30px;
        }

        .line-container {
            position: relative;
            height: calc(100% - 30px);
            width: 100%;
        }

        .line {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--light-gray);
        }

        .chart-axis {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .axis-label {
            font-size: 0.8rem;
            color: var(--dark-gray);
            text-align: center;
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

            .analytics-tools {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-controls {
                flex-direction: column;
            }

            .analytics-grid {
                grid-template-columns: 1fr;
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
            <a href="history.php" class="nav-link">Feedback History</a>
            <a href="profile.php" class="nav-link">Profile</a>
            <a href="notification.php" class="nav-link">Notifications</a>
            <a href="analytics.php" class="nav-link active">Analytics</a>
            <a href="help.php" class="nav-link">Help & Support</a>
        </div>
    </nav>

    <div class="main-container">
        <h1 class="page-title">Feedback Analytics</h1>

        <!-- Filter Tools -->
        <div class="analytics-tools">
            <div class="filter-controls">
                <select class="filter-select">
                    <option value="">All Departments</option>
                    <option value="academic">Academic Affairs</option>
                    <option value="student">Student Affairs</option>
                    <option value="library">Library</option>
                    <option value="it">IT Department</option>
                    <option value="infrastructure">Infrastructure</option>
                </select>
                <select class="filter-select">
                    <option value="">All Time</option>
                    <option value="current">Current Semester</option>
                    <option value="previous">Previous Semester</option>
                    <option value="year">Last Year</option>
                </select>
                <select class="filter-select">
                    <option value="">All Categories</option>
                    <option value="course">Course Evaluations</option>
                    <option value="facility">Facility Feedback</option>
                    <option value="service">Service Feedback</option>
                </select>
            </div>
            <button class="export-btn">Export Report</button>
        </div>

        <!-- Key Insights -->
        <div class="insights-container">
            <h2 class="insights-title">Key Insights</h2>
            <div class="insights-grid">
                <div class="insight-card">
                    <div class="insight-value">4.3/5</div>
                    <div class="insight-label">Average Rating</div>
                    <div class="insight-trend trend-up">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 5px;">
                            <path d="M7 14l5-5 5 5z"></path>
                        </svg>
                        0.2 from last semester
                    </div>
                </div>
                <div class="insight-card">
                    <div class="insight-value">29.6%</div>
                    <div class="insight-label">Implementation Rate</div>
                    <div class="insight-trend trend-up">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 5px;">
                            <path d="M7 14l5-5 5 5z"></path>
                        </svg>
                        4.2% from last semester
                    </div>
                </div>
                <div class="insight-card">
                    <div class="insight-value">12</div>
                    <div class="insight-label">Submissions This Semester</div>
                    <div class="insight-trend trend-neutral">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 5px;">
                            <path d="M5 12h14"></path>
                        </svg>
                        Same as last semester
                    </div>
                </div>
                <div class="insight-card">
                    <div class="insight-value">6 days</div>
                    <div class="insight-label">Average Response Time</div>
                    <div class="insight-trend trend-down">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 5px;">
                            <path d="M7 10l5 5 5-5z"></path>
                        </svg>
                        2 days longer than last semester
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Grid -->
        <div class="analytics-grid">
            <!-- Feedback by Department -->
            <div class="analytics-card">
                <div class="analytics-card-header">
                    <div class="card-title">Feedback by Department</div>
                    <div class="card-options">⋮</div>
                </div>
                <div class="chart-container">
                    <div class="donut-chart">
                        <div class="donut-ring"></div>
                        <div class="donut-hole">
                            <div style="font-size: 0.8rem; color: var(--dark-gray);">Total</div>
                            <div style="font-size: 1.5rem; font-weight: bold;">27</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback Status -->
            <div class="analytics-card">
                <div class="analytics-card-header">
                    <div class="card-title">Feedback Status</div>
                    <div class="card-options">⋮</div>
                </div>
                <div class="chart-container">
                    <div class="bar-chart">
                        <div class="bar" style="height: 70%;">
                            <div class="bar-value">7</div>
                            <div class="bar-label">Submitted</div>
                        </div>
                        <div class="bar" style="height: 50%;">
                            <div class="bar-value">5</div>
                            <div class="bar-label">Reviewed</div>
                        </div>
                        <div class="bar" style="height: 80%;">
                            <div class="bar-value">8</div>
                            <div class="bar-label">Implemented</div>
                        </div>
                        <div class="bar" style="height: 70%;">
                            <div class="bar-value">7</div>
                            <div class="bar-label">Archived</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Rating by Category -->
            <div class="analytics-card">
                <div class="analytics-card-header">
                    <div class="card-title">Average Rating by Category</div>
                    <div class="card-options">⋮</div>
                </div>
                <div class="chart-container">
                    <div class="bar-chart">
                        <div class="bar" style="height: 90%; background-color: #4CAF50;">
                            <div class="bar-value">4.5</div>
                            <div class="bar-label">Courses</div>
                        </div>
                        <div class="bar" style="height: 74%; background-color: #FFC107;">
                            <div class="bar-value">3.7</div>
                            <div class="bar-label">Facilities</div>
                        </div>
                        <div class="bar" style="height: 60%; background-color: #F44336;">
                            <div class="bar-value">3.0</div>
                            <div class="bar-label">Dining</div>
                        </div>
                        <div class="bar" style="height: 80%; background-color: #2196F3;">
                            <div class="bar-value">4.0</div>
                            <div class="bar-label">IT Services</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Submission Trend -->
            <div class="analytics-card">
                <div class="analytics-card-header">
                    <div class="card-title">Monthly Submission Trend</div>
                    <div class="card-options">⋮</div>
                </div>
                <div class="chart-container">
                    <!-- Simplified line chart representation -->
                    <div class="line-chart">
                        <div class="line-container">
                            <svg width="100%" height="100%" viewBox="0 0 300 200" preserveAspectRatio="none">
                                <polyline points="0,150 50,100 100,120 150,80 200,60 250,90 300,40" 
                                    fill="none" stroke="var(--maroon)" stroke-width="3"/>
                                <circle cx="0" cy="150" r="4" fill="var(--maroon)"/>
                                <circle cx="50" cy="100" r="4" fill="var(--maroon)"/>
                                <circle cx="100" cy="120" r="4" fill="var(--maroon)"/>
                                <circle cx="150" cy="80" r="4" fill="var(--maroon)"/>
                                <circle cx="200" cy="60" r="4" fill="var(--maroon)"/>
                                <circle cx="250" cy="90" r="4" fill="var(--maroon)"/>
                                <circle cx="300" cy="40" r="4" fill="var(--maroon)"/>
                            </svg>
                        </div>
                        <div class="chart-axis">
                            <div class="axis-label">Oct</div>
                            <div class="axis-label">Nov</div>
                            <div class="axis-label">Dec</div>
                            <div class="axis-label">Jan</div>
                            <div class="axis-label">Feb</div>
                            <div class="axis-label">Mar</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Full Width Analytics -->
        <div class="analytics-full">
            <div class="analytics-full-header">
                <div class="card-title">Feedback Ratings Over Time</div>
                <div class="card-options">⋮</div>
            </div>
            <div class="full-chart-container">
                <!-- Simplified line chart representation -->
                <svg width="100%" height="100%" viewBox="0 0 800 250" preserveAspectRatio="none">
                    <!-- Grid lines -->
                    <line x1="0" y1="50" x2="800" y2="50" stroke="var(--light-gray)" stroke-width="1"/>
                    <line x1="0" y1="100" x2="800" y2="100" stroke="var(--light-gray)" stroke-width="1"/>
                    <line x1="0" y1="150" x2="800" y2="150" stroke="var(--light-gray)" stroke-width="1"/>
                    <line x1="0" y1="200" x2="800" y2="200" stroke="var(--light-gray)" stroke-width="1"/>
                    
                    <!-- Academic Affairs Line -->
                    <polyline points="0,130 100,110 200,90 300,70 400,90 500,50 600,70 700,60 800,40" 
                        fill="none" stroke="var(--maroon)" stroke-width="3"/>
                    
                    <!-- Student Affairs Line -->
                    <polyline points="0,160 100,150 200,130 300,140 400,120 500,110 600,100 700,120 800,100" 
                        fill="none" stroke="#0066cc" stroke-width="3"/>
                    
                    <!-- IT Department Line -->
                    <polyline points="0,180 100,170 200,150 300,130 400,150 500,140 600,130 700,110 800,90" 
                        fill="none" stroke="#008800" stroke-width="3"/>
                    
                    <!-- Library Line -->
                    <polyline points="0,120 100,100 200,110 300,90 400,70 500,80 600,60 700,50 800,70" 
                        fill="none" stroke="#ff6600" stroke-width="3"/>
                </svg>
                
                <!-- Chart Legend -->
                <div style="display: flex; justify-content: center; margin-top: 10px; gap: 20px;">
                    <div style="display: flex; align-items: center;">
                        <div style="width: 12px; height: 12px; background-color: var(--maroon); margin-right: 5px;"></div>
                        <span style="font-size: 0.8rem;">Academic Affairs</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <div style="width: 12px; height: 12px; background-color: #0066cc; margin-right: 5px;"></div>
                        <span style="font-size: 0.8rem;">Student Affairs</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <div style="width: 12px; height: 12px; background-color: #008800; margin-right: 5px;"></div>
                        <span style="font-size: 0.8rem;">IT Department</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <div style="width: 12px; height: 12px; background-color: #ff6600; margin-right: 5px;"></div>
                        <span style="font-size: 0.8rem;">Library</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Areas -->
        <div class="analytics-full">
            <div class="analytics-full-header">
                <div class="card-title">Top Performing Areas</div>
                <div class="card-options">⋮</div>
            </div>
            <table class="feedback-table" style="margin-top: 1rem;">
                <thead>
                    <tr>
                        <th>Area</th>
                        <th>Department</th>
                        <th>Average Rating</th>
                        <th>Change</th>
                        <th>Submissions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Library Resources</td>
                        <td>Library Department</td>
                        <td><span class="feedback-item-score score-high">4.8/5</span></td>
                        <td><span class="trend-up">↑ 0.3</span></td>
                        <td>6</td>
                    </tr>
                    <tr>
                        <td>Student Activities</td>
                        <td>Student Affairs</td>
                        <td><span class="feedback-item-score score-high">4.7/5</span></td>
                        <td><span class="trend-up">↑ 0.2</span></td>
                        <td>8</td>
                    </tr>
                    <tr>
                        <td>CS225: Database Systems</td>
                        <td>Academic Affairs</td>
                        <td><span class="feedback-item-score score-high">4.5/5</span></td>
                        <td><span class="trend-neutral">→ 0.0</span></td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>IT Support Services</td>
                        <td>IT Department</td>
                        <td><span class="feedback-item-score score-high">4.3/5</span></td>
                        <td><span class="trend-up">↑ 0.5</span></td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td>MATH201: Calculus II</td>
                        <td>Academic Affairs</td>
                        <td><span class="feedback-item-score score-high">4.2/5</span></td>
                        <td><span class="trend-down">↓ 0.4</span></td>
                        <td>3</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Areas for Improvement -->
        <div class="analytics-full">
            <div class="analytics-full-header">
                <div class="card-title">Areas for Improvement</div>
                <div class="card-options">⋮</div>
            </div>
            <table class="feedback-table" style="margin-top: 1rem;">
                <thead>
                    <tr>
                        <th>Area</th>
                        <th>Department</th>
                        <th>Average Rating</th>
                        <th>Change</th>
                        <th>Submissions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Cafeteria Services</td>
                        <td>Student Affairs</td>
                        <td><span class="feedback-item-score score-low">2.8/5</span></td>
                        <td><span class="trend-down">↓ 0.6</span></td>
                        <td>9</td>
                    </tr>
                    <tr>
                        <td>Campus Wi-Fi</td>
                        <td>IT Department</td>
                        <td><span class="feedback-item-score score-low">2.9/5</span></td>
                        <td><span class="trend-up">↑ 0.2</span></td>
                        <td>7</td>
                    </tr>
                    <tr>
                        <td>Dormitory Facilities</td>
                        <td>Infrastructure</td>
                        <td><span class="feedback-item-score score-medium">3.1/5</span></td>
                        <td><span class="trend-neutral">→ 0.0</span></td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td>Sports Facilities</td>
                        <td>Infrastructure</td>
                        <td><span class="feedback-item-score score-medium">3.2/5</span></td>
                        <td><span class="trend-up">↑ 0.3</span></td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>BUS305: Business Ethics</td>
                        <td>Academic Affairs</td>
                        <td><span class="feedback-item-score score-medium">3.5/5</span></td>
                        <td><span class="trend-down">↓ 0.2</span></td>
                        <td>3</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    // Filter functionality
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() {
            console.log('Filter changed:', this.value);
        });
    });

    // Export button functionality
    document.querySelector('.export-btn').addEventListener('click', function() {
        console.log('Exporting analytics report...');
        alert('Analytics report is being prepared for download.');
    });

    // Chart options menu
    document.querySelectorAll('.card-options').forEach(option => {
        option.addEventListener('click', function(event) {
            // Create dropdown menu
            const menu = document.createElement('div');
            menu.style.cssText = `
                position: absolute;
                background: white;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                border-radius: 5px;
                padding: 0.5rem;
                z-index: 1000;
            `;
            
            // Menu items
            menu.innerHTML = `
                <div class="menu-item" style="padding: 0.5rem; cursor: pointer;">View Details</div>
                <div class="menu-item" style="padding: 0.5rem; cursor: pointer;">Export as Image</div>
                <div class="menu-item" style="padding: 0.5rem; cursor: pointer;">Refresh Data</div>
            `;

            // Position menu
            const rect = option.getBoundingClientRect();
            menu.style.top = `${rect.bottom + 5}px`;
            menu.style.left = `${rect.left}px`;

            // Add to DOM
            document.body.appendChild(menu);

            // Close menu on click outside
            const clickHandler = e => {
                if (!menu.contains(e.target)) {
                    menu.remove();
                    document.removeEventListener('click', clickHandler);
                }
            };
            document.addEventListener('click', clickHandler);

            // Menu item actions
            menu.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', () => {
                    menu.remove();
                    const action = item.textContent;
                    if(action === 'Export as Image') {
                        alert('Export feature coming soon!');
                    }
                    // Add other actions here
                });
            });
        });
    });

    // Initialize chart hover effects
    document.querySelectorAll('.bar, .donut-ring').forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.opacity = '0.8';
        });
        element.addEventListener('mouseleave', function() {
            this.style.opacity = '1';
        });
    });
</script>