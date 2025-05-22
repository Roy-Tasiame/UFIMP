<?php
/**
 * API endpoint that handles form submissions and progress saving
 */

// Include database connection and functions
include '../../settings/config.php';
include '../../functions/form_functions.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'User not authenticated'
    ]);
    exit;
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Check if form ID is provided
if (!isset($_POST['form_id']) || !is_numeric($_POST['form_id'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Invalid form ID'
    ]);
    exit;
}

$formId = (int)$_POST['form_id'];
$userId = $_SESSION['user_id'];
$action = isset($_POST['action']) ? $_POST['action'] : 'save'; // Default action is 'save'
$isComplete = ($action === 'submit'); // Is this a final submission

try {
    // Get all form fields to validate required fields
    $fields = getFormFields($conn, $formId);
    
    // If this is a final submission, validate required fields
    if ($isComplete) {
        $errors = [];
        foreach ($fields as $field) {
            if ($field['required'] == 1) {
                $fieldName = 'field-' . $field['id'];
                
                // Check if required field exists and is not empty
                if (!isset($_POST[$fieldName]) || 
                    (is_string($_POST[$fieldName]) && trim($_POST[$fieldName]) === '') ||
                    (is_array($_POST[$fieldName]) && empty($_POST[$fieldName]))) {
                    $errors[] = 'The field "' . $field['label'] . '" is required.';
                }
            }
        }
        
        // If there are validation errors, return them
        if (!empty($errors)) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Please correct the following errors:',
                'errors' => $errors
            ]);
            exit;
        }
    }
    
    // Process the form submission
    $result = saveFormResponse($conn, $formId, $userId, $_POST, $isComplete);
    
    // Return the result as JSON
    header('Content-Type: application/json');
    echo json_encode($result);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}