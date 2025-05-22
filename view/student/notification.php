<?php
    session_start();
    $name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ashesi Feedback Portal - Notifications</title>
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

        .notification-tools {
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

        .notification-actions {
            display: flex;
            gap: 0.8rem;
        }

        .action-button {
            padding: 0.6rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .action-button svg {
            width: 16px;
            height: 16px;
        }

        .mark-all-btn {
            background-color: var(--maroon);
            color: var(--white);
        }

        .refresh-btn {
            background-color: var(--light-gray);
            color: var(--black);
        }

        /* Notification List */
        .notification-list {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .notification-item {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: background-color 0.2s;
            cursor: pointer;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: var(--gray);
        }

        .notification-item.unread {
            background-color: rgba(128, 0, 0, 0.05);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-feedback {
            background-color: #e6f7ff;
            color: #0066cc;
        }

        .icon-update {
            background-color: #e6f9e6;
            color: #008800;
        }

        .icon-announcement {
            background-color: #fff2e6;
            color: #ff6600;
        }

        .icon-reminder {
            background-color: #ffe6e6;
            color: #cc0000;
        }

        .notification-content {
            flex: 1;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .notification-title {
            font-weight: bold;
            font-size: 1rem;
            color: var(--black);
        }

        .notification-time {
            color: var(--dark-gray);
            font-size: 0.8rem;
        }

        .notification-message {
            color: var(--dark-gray);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .notification-actions-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 0.8rem;
            gap: 0.8rem;
        }

        .notification-btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-view {
            background-color: var(--light-gray);
            color: var(--black);
        }

        .btn-respond {
            background-color: var(--maroon);
            color: var(--white);
        }

        .notification-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-top: 0.5rem;
        }

        .badge-feedback {
            background-color: #e6f7ff;
            color: #0066cc;
        }

        .badge-update {
            background-color: #e6f9e6;
            color: #008800;
        }

        .badge-announcement {
            background-color: #fff2e6;
            color: #ff6600;
        }

        .badge-reminder {
            background-color: #ffe6e6;
            color: #cc0000;
        }

        .no-notifications {
            text-align: center;
            padding: 3rem;
            color: var(--dark-gray);
        }

        .no-notifications svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            color: var(--medium-gray);
        }

        .no-notifications h3 {
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }

        /* Pagination */
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

            .notification-tools {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-controls {
                flex-direction: column;
            }

            .notification-item {
                flex-direction: column;
            }

            .notification-icon {
                margin-bottom: 0.5rem;
            }

            .notification-header {
                flex-direction: column;
            }

            .notification-time {
                margin-top: 0.3rem;
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
            <a href="notification.php" class="nav-link active">Notifications</a>
            <a href="analytics.php" class="nav-link">Analytics</a>
            <a href="help.php" class="nav-link">Help & Support</a>
        </div>
    </nav>

    <div class="main-container">
        <h1 class="page-title">Notifications</h1>

        <!-- Filter and Action Tools -->
        <div class="notification-tools">
            <div class="filter-controls">
                <select class="filter-select">
                    <option value="">All Notifications</option>
                    <option value="feedback">Feedback Updates</option>
                    <option value="system">System Announcements</option>
                    <option value="reminders">Reminders</option>
                    <option value="surveys">Survey Invitations</option>
                </select>
                <select class="filter-select">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="semester">This Semester</option>
                </select>
                <select class="filter-select">
                    <option value="">All Status</option>
                    <option value="read">Read</option>
                    <option value="unread">Unread</option>
                </select>
            </div>
            <div class="notification-actions">
                <button class="action-button mark-all-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    Mark All as Read
                </button>
                <button class="action-button refresh-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 4v6h-6"/>
                        <path d="M1 20v-6h6"/>
                        <path d="M3.51 9a9 9 0 0114.85-3.36L23 10"/>
                        <path d="M1 14l4.64 4.36A9 9 0 0020.49 15"/>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Notification List -->
        <div class="notification-list">
            <!-- Unread notification -->
            <div class="notification-item unread">
                <div class="notification-icon icon-update">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                        <path d="M12 16v-4"/>
                        <path d="M12 8h.01"/>
                    </svg>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <div class="notification-title">Feedback Status Update</div>
                        <div class="notification-time">Today, 10:45 AM</div>
                    </div>
                    <div class="notification-message">
                        Your feedback submission "Library Resources Feedback" has been reviewed and implemented. The Library Department has added 25 new e-books and extended weekend hours based on your suggestions.
                    </div>
                    <span class="notification-badge badge-update">Status Update</span>
                    <div class="notification-actions-container">
                        <button class="notification-btn btn-view">View Details</button>
                        <button class="notification-btn btn-respond">Provide Follow-up</button>
                    </div>
                </div>
            </div>

            <!-- Unread notification -->
            <div class="notification-item unread">
                <div class="notification-icon icon-announcement">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        <path d="M12 9v4"/>
                        <path d="M12 17h.01"/>
                    </svg>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <div class="notification-title">New Feedback Form Available</div>
                        <div class="notification-time">Today, 9:15 AM</div>
                    </div>
                    <div class="notification-message">
                        A new feedback form "End of Semester Student Experience Survey" is now available. Please complete this survey by March 30, 2025 to help us improve your campus experience.
                    </div>
                    <span class="notification-badge badge-announcement">New Survey</span>
                    <div class="notification-actions-container">
                        <button class="notification-btn btn-respond">Complete Survey</button>
                    </div>
                </div>
            </div>

            <!-- Read notification -->
            <div class="notification-item">
                <div class="notification-icon icon-reminder">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 01-3.46 0"/>
                    </svg>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <div class="notification-title">Reminder: Course Evaluation</div>
                        <div class="notification-time">Yesterday, 2:30 PM</div>
                    </div>
                    <div class="notification-message">
                        This is a friendly reminder to complete your course evaluations for the Spring 2025 semester. Your feedback is essential for improving course quality and teaching effectiveness.
                    </div>
                    <span class="notification-badge badge-reminder">Reminder</span>
                    <div class="notification-actions-container">
                        <button class="notification-btn btn-view">View Pending Evaluations</button>
                    </div>
                </div>
            </div>

            <!-- Read notification -->
            <div class="notification-item">
                <div class="notification-icon icon-feedback">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                    </svg>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <div class="notification-title">Response to Your Feedback</div>
                        <div class="notification-time">Mar 15, 2025</div>
                    </div>
                    <div class="notification-message">
                        The IT Department has responded to your "IT Services Feedback" submission. They've provided clarification on the issues you raised and outlined steps being taken to address your concerns.
                    </div>
                    <span class="notification-badge badge-feedback">Response Received</span>
                    <div class="notification-actions-container">
                        <button class="notification-btn btn-view">View Response</button>
                    </div>
                </div>
            </div>

            <!-- Read notification -->
            <div class="notification-item">
                <div class="notification-icon icon-announcement">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        <path d="M12 9v4"/>
                        <path d="M12 17h.01"/>
                    </svg>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <div class="notification-title">System Maintenance Notice</div>
                        <div class="notification-time">Mar 10, 2025</div>
                    </div>
                    <div class="notification-message">
                        The Feedback Portal will be undergoing scheduled maintenance on Sunday, March 23, 2025, from 2:00 AM to 6:00 AM GMT. During this time, the system may be intermittently unavailable.
                    </div>
                    <span class="notification-badge badge-announcement">System Announcement</span>
                </div>
            </div>

            <!-- Read notification -->
            <div class="notification-item">
                <div class="notification-icon icon-update">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                        <path d="M12 16v-4"/>
                        <path d="M12 8h.01"/>
                    </svg>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <div class="notification-title">Feedback Recognition</div>
                        <div class="notification-time">Mar 5, 2025</div>
                    </div>
                    <div class="notification-message">
                        Your feedback on "Student Activities Survey" was highlighted in the recent Academic Council meeting. The Student Affairs Department would like to thank you for your valuable contributions to improving campus life.
                    </div>
                    <span class="notification-badge badge-update">Recognition</span>
                    <div class="notification-actions-container">
                        <button class="notification-btn btn-view">View Certificate</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="paginator">
            <div class="page-item">&laquo;</div>
            <div class="page-item active">1</div>
            <div class="page-item">2</div>
            <div class="page-item">3</div>
            <div class="page-item">&raquo;</div>
        </div>
    </div>

    <script>
        // Toggle notification read/unread status
        document.querySelectorAll('.notification-item').forEach(notification => {
            notification.addEventListener('click', function() {
                this.classList.remove('unread');
            });
        });

        // Mark all as read functionality
        document.querySelector('.mark-all-btn').addEventListener('click', function() {
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.remove('unread');
            });
        });

        // Refresh functionality
        document.querySelector('.refresh-btn').addEventListener('click', function() {
            // This would typically fetch new notifications from the server
            console.log('Refreshing notifications...');
        });

        // Filter functionality
        document.querySelectorAll('.filter-select').forEach(select => {
            select.addEventListener('change', function() {
                // This would filter notifications based on selected criteria
                console.log('Filter changed:', this.value);
            });
        });

        // Pagination functionality
        const pageItems = document.querySelectorAll('.page-item');
        pageItems.forEach(item => {
            item.addEventListener('click', function() {
                if (!this.classList.contains('active') && this.textContent !== '«' && this.textContent !== '»') {
                    pageItems.forEach(pg => pg.classList.remove('active'));
                    this.classList.add('active');
                    // Additional code would go here to fetch and display the relevant page of notifications
                }
            });
        });
    </script>
</body>
</html>