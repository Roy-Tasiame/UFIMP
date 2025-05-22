<?php
    session_start();
    $name = $_SESSION['user_name'];
    $user_id = $_SESSION['user_id'];
    
    include '../../functions/total_users.php';
    $userProfile = getUserProfile($conn, $user_id);
    if ($userProfile) {
        $firstName = $userProfile['first_name'];
        $lastName = $userProfile['last_name'];
        $email = $userProfile['email'];
        $phoneNumber = $userProfile['phone_number'];
        $major = $userProfile['major'];
        $studentId = $userProfile['student_id'];
        $classYear = $userProfile['class_year'];
        $academicDepartment = $userProfile['academic_department'];
        $profileUpdated = $userProfile['profile_updated'];
        $role = $userProfile['role'];
    } else {
        // Handle error if profile not found (e.g., redirect to an error page)
        echo "User profile not found.";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ashesi Feedback Portal - Student Profile</title>
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

        .profile-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        .profile-sidebar {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            height: fit-content;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem;
            display: block;
            border: 3px solid var(--maroon);
        }

        .profile-info {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .profile-id, .profile-email, .profile-department {
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }

        .profile-stats {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--light-gray);
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-label {
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        .stat-value {
            font-weight: bold;
            color: var(--maroon);
        }

        .profile-actions {
            margin-top: 1.5rem;
        }

        .action-btn {
            display: block;
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 0.8rem;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--maroon);
            color: var(--white);
        }

        .btn-secondary {
            background-color: var(--light-gray);
            color: var(--black);
        }

        .profile-main {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .profile-section {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .section-title {
            color: var(--maroon);
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid var(--light-gray);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--light-gray);
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .preferences-section {
            padding: 1rem 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .preferences-section:last-child {
            border-bottom: none;
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
        }

        .checkbox-item input {
            margin-right: 0.5rem;
        }

        .toggle-switch {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.8rem 0;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--medium-gray);
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--maroon);
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .notification-title {
            font-weight: 500;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            background-color: var(--light-gray);
            text-decoration: none;
            color: var(--black);
        }

        .save-section {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1rem;
        }

        .verification-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background-color: #e6f7ff;
            border-radius: 5px;
            color: #0066cc;
            font-size: 0.9rem;
        }

        .badges-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .badge-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 120px;
            text-align: center;
        }

        .badge-icon {
            width: 60px;
            height: 60px;
            background-color: var(--light-gray);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        .badge-title {
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
        }

        .badge-description {
            font-size: 0.8rem;
            color: var(--dark-gray);
        }

        .badge-locked {
            opacity: 0.5;
        }

        @media (max-width: 992px) {
            .profile-container {
                grid-template-columns: 1fr;
            }
        }

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

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
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
            <a href="history.php" class="nav-link">History/Analytics</a>
            <a href="profile.php" class="nav-link active">Profile</a>
            <a href="help.php" class="nav-link">Help & Support</a>
        </div>
    </nav>

    <div class="main-container">
        <h1 class="page-title">Student Profile</h1>

        <div class="profile-container">
            <!-- Profile Sidebar -->
            <div class="profile-sidebar">
                <img src="../../assets/img/profile-placeholder.png" alt="Profile Picture" class="profile-picture">
                <div class="profile-info">
                    <h2 class="profile-name">    
                        <?php 
                            // Check if first_name and last_name are empty
                            if (empty($firstName) && empty($lastName)) {
                                // If both are empty, use the name
                                echo htmlspecialchars($name);
                            } else {
                                // Otherwise, use the first and last name
                                echo htmlspecialchars($firstName . ' ' . $lastName);
                            }
                        ?>
                        </h2>
                    <p class="profile-id">Student ID: <?php echo htmlspecialchars($studentId); ?></p>
                    <p class="profile-email"><?php echo htmlspecialchars($email); ?></p>
                    <p class="profile-department"><?php echo htmlspecialchars($academicDepartment); ?></p>
                </div>

                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-label">Class Year</span>
                        <span class="stat-value"><?php echo htmlspecialchars($classYear); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Feedback Submitted</span>
                        <span class="stat-value">27</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Current Semester</span>
                        <span class="stat-value">Spring 2025</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Account Status</span>
                        <span class="stat-value">Active</span>
                    </div>
                </div>

                <div class="profile-actions">
                    <button class="action-btn btn-primary" id="edit-profile-btn">Edit Profile</button>
                    <button class="action-btn btn-secondary">Change Password</button>
                </div>
            </div>

            <!-- Main Profile Content -->
            <div class="profile-main">
                <!-- Personal Information Section -->
                <div class="profile-section">
                    <h3 class="section-title">Personal Information</h3>
                    <form id="profile-form" method="POST" action="../../actions/update_profile.php">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($firstName); ?>" id="first-name-input" name="first_name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($lastName); ?>" id="last-name-input" name="last_name">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" readonly id="email-input" name="email">
                                <div class="verification-info">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 14c-3.3 0-6-2.7-6-6s2.7-6 6-6 6 2.7 6 6-2.7 6-6 6z"/>
                                        <path d="M7 11h2V5H7v6z"/>
                                        <circle cx="8" cy="3" r="1"/>
                                    </svg>
                                    Email verified with Ashesi University
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" value="<?php echo htmlspecialchars($phoneNumber); ?>" id="phone-number-input" name="phone_number">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Major/Program</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($major); ?>" id="major-input" id="major-input" name="major">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Student ID</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($studentId); ?>" id="student-id-input" name="student_id">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Class Year</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($classYear); ?>" id="class-year-input" name="class_year">
                            </div>
                        </div>

                        <div class="save-section">
                            <button type="button" class="action-btn btn-secondary" id="cancel-btn" style="display: none;">Cancel</button>
                            <button type="submit" class="action-btn btn-primary" id="save-btn" style="display: none;">Save Changes</button>
                        </div>
                    </form>
                </div>            
            </div>
        </div>
    </div>
    <script>
        // Toggle edit mode when 'Edit Profile' button is clicked
        document.getElementById('edit-profile-btn').addEventListener('click', function() {
            var fields = document.querySelectorAll('#profile-form input');
            fields.forEach(function(field) {
                field.readOnly = false; // Make fields editable
            });

            // Show the Save and Cancel buttons
            document.getElementById('save-btn').style.display = 'inline-block';
            document.getElementById('cancel-btn').style.display = 'inline-block';
            document.getElementById('edit-profile-btn').style.display = 'none';
        });

        // Cancel editing
        document.getElementById('cancel-btn').addEventListener('click', function() {
            var fields = document.querySelectorAll('#profile-form input');
            fields.forEach(function(field) {
                field.readOnly = true; // Make fields readonly
            });

            // Hide the Save and Cancel buttons
            document.getElementById('save-btn').style.display = 'none';
            document.getElementById('cancel-btn').style.display = 'none';
            document.getElementById('edit-profile-btn').style.display = 'inline-block';
        });
    </script>

</body>
</html>