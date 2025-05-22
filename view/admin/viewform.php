<?php
include '../../functions/totalforms.php';

// Check if form ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to my forms page if no ID is provided
    header('Location: myforms.php');
    exit;
}

$form_id = isset($_GET['id']) ? $_GET['id'] : null;

// Function to get form details
function getFormDetails($conn, $form_id) {
    
    // Placeholder data for demonstration
    return [
        'id' => $form_id,
        'title' => 'Student Satisfaction Survey',
        'created_at' => '2025-04-10 14:30:00',
        'updated_at' => '2025-04-12 09:15:00'
    ];
}

// Function to get form fields
function getFormFields($conn, $form_id) {
    // This is a placeholder function
    // In real implementation, you would query the database for fields of the form with the given ID
    
    // Placeholder data for demonstration
    return [
        [
            'id' => 1,
            'form_id' => $form_id,
            'label' => 'Full Name',
            'type' => 'text',
            'required' => 1,
            'order_position' => 1,
            'created_at' => '2025-04-10 14:30:00',
            'updated_at' => '2025-04-10 14:30:00'
        ],
        [
            'id' => 2,
            'form_id' => $form_id,
            'label' => 'Email Address',
            'type' => 'email',
            'required' => 1,
            'order_position' => 2,
            'created_at' => '2025-04-10 14:30:00',
            'updated_at' => '2025-04-10 14:30:00'
        ],
        [
            'id' => 3,
            'form_id' => $form_id,
            'label' => 'Year of Study',
            'type' => 'select',
            'required' => 1,
            'order_position' => 3,
            'created_at' => '2025-04-10 14:30:00',
            'updated_at' => '2025-04-10 14:30:00'
        ],
        [
            'id' => 4,
            'form_id' => $form_id,
            'label' => 'How satisfied are you with the university facilities?',
            'type' => 'radio',
            'required' => 1,
            'order_position' => 4,
            'created_at' => '2025-04-10 14:30:00',
            'updated_at' => '2025-04-10 14:30:00'
        ],
        [
            'id' => 5,
            'form_id' => $form_id,
            'label' => 'Which services would you like to see improved?',
            'type' => 'checkbox',
            'required' => 0,
            'order_position' => 5,
            'created_at' => '2025-04-10 14:30:00',
            'updated_at' => '2025-04-10 14:30:00'
        ],
        [
            'id' => 6,
            'form_id' => $form_id,
            'label' => 'Additional comments or suggestions:',
            'type' => 'textarea',
            'required' => 0,
            'order_position' => 6,
            'created_at' => '2025-04-10 14:30:00',
            'updated_at' => '2025-04-10 14:30:00'
        ]
    ];
}

// Function to get field options
function getFieldOptions($conn, $field_id) {
    // This is a placeholder function
    // In real implementation, you would query the database for options of the field with the given ID
    
    // Placeholder data for demonstration
    $options = [];
    
    // Year of Study options
    if ($field_id == 3) {
        $options = [
            ['id' => 1, 'field_id' => 3, 'option_text' => 'First Year', 'order_position' => 1],
            ['id' => 2, 'field_id' => 3, 'option_text' => 'Second Year', 'order_position' => 2],
            ['id' => 3, 'field_id' => 3, 'option_text' => 'Third Year', 'order_position' => 3],
            ['id' => 4, 'field_id' => 3, 'option_text' => 'Fourth Year', 'order_position' => 4]
        ];
    }
    // Satisfaction radio options
    else if ($field_id == 4) {
        $options = [
            ['id' => 5, 'field_id' => 4, 'option_text' => 'Very Satisfied', 'order_position' => 1],
            ['id' => 6, 'field_id' => 4, 'option_text' => 'Satisfied', 'order_position' => 2],
            ['id' => 7, 'field_id' => 4, 'option_text' => 'Neutral', 'order_position' => 3],
            ['id' => 8, 'field_id' => 4, 'option_text' => 'Dissatisfied', 'order_position' => 4],
            ['id' => 9, 'field_id' => 4, 'option_text' => 'Very Dissatisfied', 'order_position' => 5]
        ];
    }
    // Services checkbox options
    else if ($field_id == 5) {
        $options = [
            ['id' => 10, 'field_id' => 5, 'option_text' => 'Library', 'order_position' => 1],
            ['id' => 11, 'field_id' => 5, 'option_text' => 'Cafeteria', 'order_position' => 2],
            ['id' => 12, 'field_id' => 5, 'option_text' => 'Sports Facilities', 'order_position' => 3],
            ['id' => 13, 'field_id' => 5, 'option_text' => 'Technology Resources', 'order_position' => 4],
            ['id' => 14, 'field_id' => 5, 'option_text' => 'Student Housing', 'order_position' => 5]
        ];
    }
    
    return $options;
}

// Get form details, fields, and options
$form = getFormDetails($conn, $form_id);
$fields = getFormFields($conn, $form_id);

// Prepare form fields with their options
foreach ($fields as &$field) {
    if (in_array($field['type'], ['select', 'radio', 'checkbox'])) {
        $field['options'] = getFieldOptions($conn, $field['id']);
    } else {
        $field['options'] = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Form - <?php echo htmlspecialchars($form['title']); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="viewform.css">
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
                <div class="breadcrumb">
                    <a href="myforms.php">My Forms</a>
                    <i class="fas fa-chevron-right"></i>
                    <span>View Form</span>
                </div>
                <div class="action-buttons">
                    <a href="editform.php?id=<?php echo $form_id; ?>" class="btn btn-outline">
                        <i class="fas fa-edit"></i> Edit Form
                    </a>
                    <a href="submissions.php?id=<?php echo $form_id; ?>" class="btn btn-primary">
                        <i class="fas fa-chart-bar"></i> View Submissions
                    </a>
                </div>
            </div>

            <div class="form-container">
                <div class="form-header">
                    <h1 class="form-title"><?php echo htmlspecialchars($form['title']); ?></h1>
                    <div class="form-meta">
                        <span class="meta-item">
                            <i class="fas fa-calendar"></i> Created: <?php echo date('M d, Y', strtotime($form['created_at'])); ?>
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-edit"></i> Last Updated: <?php echo date('M d, Y', strtotime($form['updated_at'])); ?>
                        </span>
                    </div>
                </div>

                <div class="form-preview">
                    <form class="preview-form">
                        <?php foreach ($fields as $field): ?>
                            <div class="form-field">
                                <label for="field_<?php echo $field['id']; ?>" class="field-label">
                                    <?php echo htmlspecialchars($field['label']); ?>
                                    <?php if ($field['required']): ?>
                                        <span class="required">*</span>
                                    <?php endif; ?>
                                </label>
                                
                                <?php if ($field['type'] === 'text'): ?>
                                    <input type="text" id="field_<?php echo $field['id']; ?>" class="form-input" placeholder="Enter your answer" disabled>
                                
                                <?php elseif ($field['type'] === 'email'): ?>
                                    <input type="email" id="field_<?php echo $field['id']; ?>" class="form-input" placeholder="Enter your email" disabled>
                                
                                <?php elseif ($field['type'] === 'select'): ?>
                                    <select id="field_<?php echo $field['id']; ?>" class="form-select" disabled>
                                        <option value="" disabled selected>Select an option</option>
                                        <?php foreach ($field['options'] as $option): ?>
                                            <option value="<?php echo $option['id']; ?>"><?php echo htmlspecialchars($option['option_text']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                
                                <?php elseif ($field['type'] === 'radio'): ?>
                                    <div class="radio-group">
                                        <?php foreach ($field['options'] as $option): ?>
                                            <div class="radio-option">
                                                <input type="radio" id="option_<?php echo $option['id']; ?>" name="field_<?php echo $field['id']; ?>" value="<?php echo $option['id']; ?>" disabled>
                                                <label for="option_<?php echo $option['id']; ?>"><?php echo htmlspecialchars($option['option_text']); ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                
                                <?php elseif ($field['type'] === 'checkbox'): ?>
                                    <div class="checkbox-group">
                                        <?php foreach ($field['options'] as $option): ?>
                                            <div class="checkbox-option">
                                                <input type="checkbox" id="option_<?php echo $option['id']; ?>" name="field_<?php echo $field['id']; ?>[]" value="<?php echo $option['id']; ?>" disabled>
                                                <label for="option_<?php echo $option['id']; ?>"><?php echo htmlspecialchars($option['option_text']); ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                
                                <?php elseif ($field['type'] === 'textarea'): ?>
                                    <textarea id="field_<?php echo $field['id']; ?>" class="form-textarea" rows="4" placeholder="Enter your answer" disabled></textarea>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>

                        <div class="form-buttons">
                            <button type="button" class="btn btn-primary" disabled>Submit</button>
                        </div>
                    </form>
                </div>

                <div class="form-actions">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-share"></i>
                        </div>
                        <div class="action-content">
                            <h3>Share Form</h3>
                            <p>Copy the link below and share it with your audience</p>
                            <div class="share-link">
                                <input type="text" value="https://ashesi.edu/forms/<?php echo $form_id; ?>" readonly>
                                <button class="copy-btn" onclick="copyToClipboard()">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="qr-code">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://ashesi.edu/forms/<?php echo $form_id; ?>" alt="QR Code">
                                <a href="#" class="download-link">Download QR Code</a>
                            </div>
                        </div>
                    </div>

                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="action-content">
                            <h3>Form Settings</h3>
                            <div class="settings-options">
                                <a href="settings.php?id=<?php echo $form_id; ?>&setting=notifications" class="setting-link">
                                    <i class="fas fa-bell"></i> Notifications
                                </a>
                                <a href="settings.php?id=<?php echo $form_id; ?>&setting=access" class="setting-link">
                                    <i class="fas fa-lock"></i> Access Control
                                </a>
                                <a href="settings.php?id=<?php echo $form_id; ?>&setting=confirmation" class="setting-link">
                                    <i class="fas fa-check-circle"></i> Confirmation Message
                                </a>
                                <a href="settings.php?id=<?php echo $form_id; ?>&setting=export" class="setting-link">
                                    <i class="fas fa-download"></i> Export Options
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function copyToClipboard() {
            const shareInput = document.querySelector('.share-link input');
            shareInput.select();
            document.execCommand('copy');
            
            const copyBtn = document.querySelector('.copy-btn');
            const originalHTML = copyBtn.innerHTML;
            
            copyBtn.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                copyBtn.innerHTML = originalHTML;
            }, 2000);
        }
    </script>
</body>
</html>