<?php
    include '../../functions/totalforms.php';
    include '../../functions/form_functions.php'; // Added new functions file
    session_start();
    $name = $_SESSION['user_name'];
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ashesi Forms</title>
    <style>
        :root {
            --maroon: #800000;
            --black: #000000;
            --white: #ffffff;
            --gray: #f5f5f5;
            --light-gray: #e0e0e0;
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

        .welcome-section {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .welcome-section h1 {
            color: var(--maroon);
            margin-bottom: 1rem;
        }

        .welcome-section p {
            color: #666;
            line-height: 1.6;
        }

        .forms-section {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .form-card {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .form-card-header {
            background-color: var(--maroon);
            color: var(--white);
            padding: 1.5rem;
        }

        .form-card-header h3 {
            margin-bottom: 0.5rem;
        }

        .form-card-header p {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .form-card-body {
            padding: 1.5rem;
        }

        .form-card-body p {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .status-new {
            background-color: #e6f7ff;
            color: #0066cc;
        }

        .status-completed {
            background-color: #e6f9e6;
            color: #008800;
        }

        .status-pending {
            background-color: #fff2e6;
            color: #ff6600;
        }

        .status-expired {
            background-color: #f2f2f2;
            color: #666666;
        }

        .form-card-footer {
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--light-gray);
        }

        .form-card-footer .due-date {
            font-size: 0.9rem;
            color: #666;
        }

        .form-card-footer .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--maroon);
            color: var(--white);
        }

        .btn-secondary {
            background-color: var(--light-gray);
            color: #333;
        }

        .form-view-container {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin-top: 2rem;
            display: none; /* Hidden by default */
        }

        .form-view-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--light-gray);
        }

        .form-view-header h2 {
            color: var(--maroon);
        }

        .close-form-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .form-questions {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-question {
            padding: 1rem;
            border-radius: 5px;
            background-color: var(--gray);
        }

        .form-question label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .form-question input[type="text"],
        .form-question textarea,
        .form-question select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .rating-options {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .rating-option {
            display: flex;
            align-items: center;
        }

        .rating-option input {
            margin-right: 0.5rem;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .checkbox-option {
            display: flex;
            align-items: center;
        }

        .checkbox-option input {
            margin-right: 0.5rem;
        }

        .form-actions {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        /* Success message styling */
        .success-message {
            background-color: #e6f9e6;
            color: #008800;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            display: none;
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

            .main-container {
                padding: 0 1rem;
            }

            .forms-section {
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
            <a href="dashboard.php" class="nav-link active">Dashboard</a>
            <a href="history.php" class="nav-link">Feedback History</a>
            <a href="profile.php" class="nav-link">Profile</a>
            <a href="help.php" class="nav-link">Help & Support</a>
        </div>
    </nav>

    <div class="main-container">
        <section class="welcome-section">
            <h1>Welcome to ASHFORM - Ashesi's Feedback Portal</h1>
            <p>Here you can fill out forms, provide feedback, and share your insights to help improve the Ashesi community. Your feedback matters and will help shape the future of our programs and services.</p>
        </section>

        <h2 style="margin-bottom: 1.5rem; color: var(--maroon);">Available Forms</h2>
        
        <!-- Success message container -->
        <div id="success-message" class="success-message">
            Your form has been submitted successfully!
        </div>
        
        <section class="forms-section">
            <!-- Form Cards will be displayed here -->
            <?php
                $forms = getAllForms($conn);
                foreach ($forms as $form):
                    // Check if the user has already submitted this form
                    $formStatus = checkFormSubmissionStatus($conn, $form['id'], $_SESSION['user_id']);
            ?>
            <div class="form-card">
                <div class="form-card-header">
                    <h3><?php echo htmlspecialchars($form['title']); ?></h3>
                    <p><?php echo $form['user_name'];?></p>
                    <p style="display: none;">Form ID: <?php echo $form['id']; ?></p>
                </div>
                <div class="form-card-body">
                    <?php if ($formStatus === 'completed'): ?>
                        <div class="status-badge status-completed">Completed</div>
                    <?php elseif ($formStatus === 'pending'): ?>
                        <div class="status-badge status-pending">In Progress</div>
                    <?php else: ?>
                        <div class="status-badge status-new">New</div>
                    <?php endif; ?>
                    
                    <p><?php echo !empty($form['description']) ? htmlspecialchars($form['description']) : 'Please provide your feedback on this form.'; ?></p>
                </div>
                <div class="form-card-footer">
                    <div class="due-date">Due: <?php echo isset($form['due_date']) ? $form['due_date'] : 'No deadline'; ?></div>
                    <button class="btn btn-primary" onclick="openForm(<?php echo $form['id']; ?>, '<?php echo addslashes($form['title']); ?>')">
                        <?php echo ($formStatus === 'completed') ? 'View' : (($formStatus === 'pending') ? 'Continue' : 'Start'); ?>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </section>

        <!-- Dynamic Form Container -->
        <div id="form-view" class="form-view-container">
            <div class="form-view-header">
                <h2 id="form-title">Form Title</h2>
                <button class="close-form-btn" onclick="closeForm()">×</button>
            </div>
            
            <form id="feedback-form" method="post">
                <input type="hidden" id="form_id" name="form_id" value="">
                <div class="form-questions" id="form-questions">
                    <!-- Questions will be dynamically inserted here -->
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="saveFormProgress()">Save Progress</button>
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentFormId = null;
        let formData = {};
        
        // Function to open the form
        function openForm(formId, formTitle) {
            currentFormId = formId;
            document.getElementById('form_id').value = formId;
            document.getElementById('form-title').textContent = formTitle;
            document.getElementById('form-view').style.display = 'block';
            
            // Scroll to the form
            document.getElementById('form-view').scrollIntoView({ behavior: 'smooth' });
            
            // Load form fields
            loadFormFields(formId);
        }
        
        // Function to close the form
        function closeForm() {
            document.getElementById('form-view').style.display = 'none';
            document.getElementById('form-questions').innerHTML = '';
            currentFormId = null;
        }
        
        // Function to load form fields from the server
        function loadFormFields(formId) {
            // Show loading indicator or message
            document.getElementById('form-questions').innerHTML = '<p>Loading form fields...</p>';
            
            // Fetch form fields using AJAX
            fetch(`get_form_fields.php?form_id=${formId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderFormFields(data.fields, data.savedResponses);
                    } else {
                        document.getElementById('form-questions').innerHTML = `<p>Error: ${data.message}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error loading form fields:', error);
                    document.getElementById('form-questions').innerHTML = '<p>Failed to load form fields. Please try again.</p>';
                });
        }
        
        // Function to render form fields in the DOM
        function renderFormFields(fields, savedResponses = {}) {
            const formQuestionsContainer = document.getElementById('form-questions');
            formQuestionsContainer.innerHTML = '';
            
            if (fields.length === 0) {
                formQuestionsContainer.innerHTML = '<p>This form has no questions.</p>';
                return;
            }
            
            fields.forEach(field => {
                const fieldDiv = document.createElement('div');
                fieldDiv.className = 'form-question';
                
                // Create label
                const label = document.createElement('label');
                label.setAttribute('for', `field-${field.id}`);
                label.textContent = field.label;
                if (field.required) {
                    const requiredSpan = document.createElement('span');
                    requiredSpan.textContent = ' *';
                    requiredSpan.style.color = 'red';
                    label.appendChild(requiredSpan);
                }
                fieldDiv.appendChild(label);
                
                // Create field input based on type
                let fieldInput;
                
                switch (field.type) {
                    case 'text':
                        fieldInput = document.createElement('input');
                        fieldInput.type = 'text';
                        fieldInput.id = `field-${field.id}`;
                        fieldInput.name = `field-${field.id}`;
                        fieldInput.required = field.required;
                        if (savedResponses[field.id]) {
                            fieldInput.value = savedResponses[field.id];
                        }
                        fieldDiv.appendChild(fieldInput);
                        break;
                        
                    case 'textarea':
                        fieldInput = document.createElement('textarea');
                        fieldInput.id = `field-${field.id}`;
                        fieldInput.name = `field-${field.id}`;
                        fieldInput.rows = 5;
                        fieldInput.required = field.required;
                        if (savedResponses[field.id]) {
                            fieldInput.value = savedResponses[field.id];
                        }
                        fieldDiv.appendChild(fieldInput);
                        break;
                        
                    case 'radio':
                        const radioGroup = document.createElement('div');
                        radioGroup.className = 'rating-options';
                        
                        field.options.forEach(option => {
                            const optionDiv = document.createElement('div');
                            optionDiv.className = 'rating-option';
                            
                            const radioInput = document.createElement('input');
                            radioInput.type = 'radio';
                            radioInput.id = `option-${option.id}`;
                            radioInput.name = `field-${field.id}`;
                            radioInput.value = option.option_text;
                            radioInput.required = field.required;
                            
                            if (savedResponses[field.id] === option.option_text) {
                                radioInput.checked = true;
                            }
                            
                            const radioLabel = document.createElement('label');
                            radioLabel.setAttribute('for', `option-${option.id}`);
                            radioLabel.textContent = option.option_text;
                            
                            optionDiv.appendChild(radioInput);
                            optionDiv.appendChild(radioLabel);
                            radioGroup.appendChild(optionDiv);
                        });
                        
                        fieldDiv.appendChild(radioGroup);
                        break;
                        
                    case 'checkbox':
                        const checkboxGroup = document.createElement('div');
                        checkboxGroup.className = 'checkbox-group';
                        
                        field.options.forEach(option => {
                            const optionDiv = document.createElement('div');
                            optionDiv.className = 'checkbox-option';
                            
                            const checkboxInput = document.createElement('input');
                            checkboxInput.type = 'checkbox';
                            checkboxInput.id = `option-${option.id}`;
                            checkboxInput.name = `field-${field.id}[]`;
                            checkboxInput.value = option.option_text;
                            
                            if (savedResponses[field.id] && 
                                Array.isArray(savedResponses[field.id]) && 
                                savedResponses[field.id].includes(option.option_text)) {
                                checkboxInput.checked = true;
                            }
                            
                            const checkboxLabel = document.createElement('label');
                            checkboxLabel.setAttribute('for', `option-${option.id}`);
                            checkboxLabel.textContent = option.option_text;
                            
                            optionDiv.appendChild(checkboxInput);
                            optionDiv.appendChild(checkboxLabel);
                            checkboxGroup.appendChild(optionDiv);
                        });
                        
                        fieldDiv.appendChild(checkboxGroup);
                        break;
                        
                    case 'select':
                        const selectInput = document.createElement('select');
                        selectInput.id = `field-${field.id}`;
                        selectInput.name = `field-${field.id}`;
                        selectInput.required = field.required;
                        
                        // Add default empty option
                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = '-- Select an option --';
                        selectInput.appendChild(defaultOption);
                        
                        field.options.forEach(option => {
                            const optionEl = document.createElement('option');
                            optionEl.value = option.option_text;
                            optionEl.textContent = option.option_text;
                            
                            if (savedResponses[field.id] === option.option_text) {
                                optionEl.selected = true;
                            }
                            
                            selectInput.appendChild(optionEl);
                        });
                        
                        fieldDiv.appendChild(selectInput);
                        break;
                }
                
                formQuestionsContainer.appendChild(fieldDiv);
            });
            
            // Set up form submission handler
            setupFormSubmission();
        }
        
        // Function to set up form submission handling
        function setupFormSubmission() {
            const form = document.getElementById('feedback-form');
            
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                
                // Collect form data
                const formData = new FormData(form);
                
                // Add the form ID
                formData.append('form_id', currentFormId);
                formData.append('action', 'submit');
                
                // Submit the form using AJAX
                fetch('process_form_submission.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const successMessage = document.getElementById('success-message');
                        successMessage.style.display = 'block';
                        successMessage.textContent = data.message;
                        
                        // Close the form
                        closeForm();
                        
                        // Scroll to the success message
                        successMessage.scrollIntoView({ behavior: 'smooth' });
                        
                        // Hide success message after 5 seconds
                        setTimeout(() => {
                            successMessage.style.display = 'none';
                            // Refresh the page to update form status
                            location.reload();
                        }, 5000);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error submitting form:', error);
                    alert('Failed to submit form. Please try again.');
                });
            });
        }
        
        // Function to save form progress
        function saveFormProgress() {
            const form = document.getElementById('feedback-form');
            const formData = new FormData(form);
            
            // Add the form ID and action type
            formData.append('form_id', currentFormId);
            formData.append('action', 'save');
            
            // Save progress using AJAX
            fetch('process_form_submission.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show temporary success message for saving
                    alert(data.message);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error saving form progress:', error);
                alert('Failed to save form progress. Please try again.');
            });
        }
    </script>
</body>
</html>