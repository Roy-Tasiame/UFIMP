<?php
    session_start();
    $name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ashesi Feedback Portal - Help & Support</title>
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

        /* Help and Support Specific Styles */
        .help-container {
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 2rem;
        }

        .help-sidebar {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            height: fit-content;
        }

        .help-sidebar h3 {
            color: var(--maroon);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--light-gray);
        }

        .help-menu {
            list-style: none;
        }

        .help-menu li {
            margin-bottom: 0.5rem;
        }

        .help-menu a {
            display: block;
            padding: 0.8rem;
            color: var(--black);
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .help-menu a:hover {
            background-color: var(--light-gray);
        }

        .help-menu a.active {
            background-color: var(--maroon);
            color: var(--white);
        }

        .help-content {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }

        .help-content h2 {
            color: var(--maroon);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--light-gray);
        }

        .faq-section {
            margin-bottom: 2rem;
        }

        .faq-item {
            margin-bottom: 1.5rem;
        }

        .faq-question {
            background-color: var(--gray);
            padding: 1rem;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-question::after {
            content: '+';
            font-size: 1.5rem;
            color: var(--maroon);
        }

        .faq-question.active::after {
            content: '-';
        }

        .faq-answer {
            padding: 1rem;
            background-color: var(--white);
            border: 1px solid var(--light-gray);
            border-radius: 0 0 5px 5px;
            margin-top: -5px;
            display: none;
        }

        .faq-answer.active {
            display: block;
        }

        .contact-section {
            margin-top: 2rem;
            padding: 1.5rem;
            background-color: var(--gray);
            border-radius: 10px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .contact-card {
            background-color: var(--white);
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .contact-card h4 {
            color: var(--maroon);
            margin-bottom: 0.8rem;
        }

        .contact-card p {
            margin-bottom: 0.5rem;
            color: var(--dark-gray);
        }

        .contact-card a {
            color: var(--maroon);
            text-decoration: none;
            font-weight: bold;
        }

        .contact-card a:hover {
            text-decoration: underline;
        }

        .support-form {
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--light-gray);
            border-radius: 5px;
            font-size: 1rem;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .form-select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--light-gray);
            border-radius: 5px;
            font-size: 1rem;
            background-color: var(--white);
        }

        .submit-btn {
            padding: 0.8rem 2rem;
            background-color: var(--maroon);
            color: var(--white);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
        }

        .submit-btn:hover {
            background-color: #600000;
        }

        .guide-section {
            margin-top: 2rem;
        }

        .guide-card {
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .guide-icon {
            width: 64px;
            height: 64px;
            background-color: var(--maroon);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.5rem;
        }

        .guide-content h4 {
            color: var(--maroon);
            margin-bottom: 0.5rem;
        }

        .guide-content p {
            color: var(--dark-gray);
            margin-bottom: 0.8rem;
        }

        .guide-link {
            color: var(--maroon);
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
        }

        .guide-link::after {
            content: '→';
            margin-left: 0.5rem;
        }

        .guide-link:hover {
            text-decoration: underline;
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

            .help-container {
                grid-template-columns: 1fr;
            }

            .help-sidebar {
                margin-bottom: 1.5rem;
            }

            .contact-grid {
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
            <a href="help.php" class="nav-link active">Help & Support</a>
        </div>
    </nav>

    <div class="main-container">
        <h1 class="page-title">Help & Support</h1>

        <div class="help-container">
            <!-- Help Sidebar -->
            <div class="help-sidebar">
                <h3>Help Categories</h3>
                <ul class="help-menu">
                    <li><a href="#faq" class="active">Frequently Asked Questions</a></li>
                    <li><a href="#guides">User Guides</a></li>
                    <li><a href="#contact">Contact Support</a></li>
                    <li><a href="#feedback">Report an Issue</a></li>
                    <li><a href="#tutorials">Video Tutorials</a></li>
                    <li><a href="#resources">Resources</a></li>
                </ul>
            </div>

            <!-- Help Content -->
            <div class="help-content">
                <section id="faq" class="faq-section">
                    <h2>Frequently Asked Questions</h2>
                    
                    <div class="faq-item">
                        <div class="faq-question">How do I submit a new feedback?</div>
                        <div class="faq-answer">
                            To submit new feedback, go to the Dashboard and click on the "New Feedback" button. Select the appropriate department and form type, then fill out the required information. Once completed, click "Submit" to send your feedback.
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">How can I check the status of my feedback?</div>
                        <div class="faq-answer">
                            You can check the status of your feedback by visiting the "Feedback History" page. Each submission will show its current status: Submitted, Reviewed, Implemented, or Archived. You can also filter the list by status to find specific feedback items.
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">Can I edit my feedback after submission?</div>
                        <div class="faq-answer">
                            Once feedback is submitted, it cannot be edited. However, if you need to provide additional information or make corrections, you can view your submission and add comments or upload additional documents.
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">How do I download a copy of my feedback?</div>
                        <div class="faq-answer">
                            To download a copy of your feedback, go to the "Feedback History" page, find the relevant submission, and click the "Download" button. This will generate a PDF file containing all the details of your feedback submission.
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">What happens after I submit feedback?</div>
                        <div class="faq-answer">
                            After submission, your feedback is routed to the relevant department for review. You'll receive a notification when the status changes. The department may reach out for additional information if needed. Once reviewed, the feedback will either be implemented, archived, or marked for further consideration.
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">How are feedback ratings calculated?</div>
                        <div class="faq-answer">
                            Feedback ratings are calculated based on several factors, including the completeness of your submission, the relevance to the department, the clarity of your comments, and the actionability of your suggestions. Ratings range from 1 to 5, with 5 being the highest.
                        </div>
                    </div>
                </section>

                <section id="guides" class="guide-section">
                    <h2>User Guides</h2>
                    
                    <div class="guide-card">
                        <div class="guide-icon">📝</div>
                        <div class="guide-content">
                            <h4>How to Submit Effective Feedback</h4>
                            <p>Learn best practices for submitting clear, actionable feedback that gets results.</p>
                            <a href="#" class="guide-link">Read Guide</a>
                        </div>
                    </div>

                    <div class="guide-card">
                        <div class="guide-icon">📊</div>
                        <div class="guide-content">
                            <h4>Understanding Analytics</h4>
                            <p>A detailed guide on interpreting your feedback analytics and using data to improve future submissions.</p>
                            <a href="#" class="guide-link">Read Guide</a>
                        </div>
                    </div>

                    <div class="guide-card">
                        <div class="guide-icon">🔔</div>
                        <div class="guide-content">
                            <h4>Managing Notifications</h4>
                            <p>Learn how to customize your notification preferences and stay updated on your feedback status.</p>
                            <a href="#" class="guide-link">Read Guide</a>
                        </div>
                    </div>

                    <div class="guide-card">
                        <div class="guide-icon">🗂️</div>
                        <div class="guide-content">
                            <h4>Organizing Your Feedback History</h4>
                            <p>Tips and tricks for managing your feedback history, including filtering, searching, and archiving.</p>
                            <a href="#" class="guide-link">Read Guide</a>
                        </div>
                    </div>
                </section>

                <section id="contact" class="contact-section">
                    <h2>Contact Support</h2>
                    <p>Need additional help? Reach out to our support team or department contacts.</p>
                    
                    <div class="contact-grid">
                        <div class="contact-card">
                            <h4>Technical Support</h4>
                            <p>For technical issues with the feedback portal</p>
                            <p>Email: <a href="mailto:tech.support@ashesi.edu.gh">tech.support@ashesi.edu.gh</a></p>
                            <p>Phone: +233 20 123 4567</p>
                        </div>
                        
                        <div class="contact-card">
                            <h4>Academic Affairs</h4>
                            <p>For questions related to course evaluations</p>
                            <p>Email: <a href="mailto:academic@ashesi.edu.gh">academic@ashesi.edu.gh</a></p>
                            <p>Phone: +233 20 123 4568</p>
                        </div>
                        
                        <div class="contact-card">
                            <h4>Student Affairs</h4>
                            <p>For questions about student services feedback</p>
                            <p>Email: <a href="mailto:student.affairs@ashesi.edu.gh">student.affairs@ashesi.edu.gh</a></p>
                            <p>Phone: +233 20 123 4569</p>
                        </div>
                    </div>
                </section>

                <section id="feedback" class="support-form">
                    <h2>Report an Issue</h2>
                    <p>Encountered a problem with the feedback portal? Let us know so we can help.</p>
                    
                    <form action="#" method="post">
                        <div class="form-group">
                            <label for="issue-type" class="form-label">Issue Type</label>
                            <select id="issue-type" class="form-select">
                                <option value="">Select an issue type</option>
                                <option value="technical">Technical Issue</option>
                                <option value="access">Access Problem</option>
                                <option value="submission">Submission Error</option>
                                <option value="notification">Notification Issue</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="issue-title" class="form-label">Issue Title</label>
                            <input type="text" id="issue-title" class="form-control" placeholder="Brief description of the issue">
                        </div>
                        
                        <div class="form-group">
                            <label for="issue-description" class="form-label">Issue Description</label>
                            <textarea id="issue-description" class="form-control" placeholder="Please provide as much detail as possible"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="issue-attachment" class="form-label">Attachment (optional)</label>
                            <input type="file" id="issue-attachment" class="form-control">
                        </div>
                        
                        <button type="submit" class="submit-btn">Submit Report</button>
                    </form>
                </section>
            </div>
        </div>
    </div>

    <script>
        // FAQ Accordion functionality
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', function() {
                this.classList.toggle('active');
                const answer = this.nextElementSibling;
                if (answer.style.display === 'block') {
                    answer.style.display = 'none';
                    answer.classList.remove('active');
                } else {
                    answer.style.display = 'block';
                    answer.classList.add('active');
                }
            });
        });

        // Sidebar navigation
        document.querySelectorAll('.help-menu a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all links
                document.querySelectorAll('.help-menu a').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Add active class to clicked link
                this.classList.add('active');
                
                // Get the target section ID
                const targetId = this.getAttribute('href').substring(1);
                
                // Scroll to the target section
                document.getElementById(targetId).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>