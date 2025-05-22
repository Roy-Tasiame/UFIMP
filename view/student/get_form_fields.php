<?php
/**
 * API endpoint that returns form fields and any saved responses for a specific form
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

// Check if form ID is provided
if (!isset($_GET['form_id']) || !is_numeric($_GET['form_id'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Invalid form ID'
    ]);
    exit;
}

$formId = (int)$_GET['form_id'];
$userId = $_SESSION['user_id'];

try {
    // Get all fields for this form
    $fields = getFormFields($conn, $formId);
    
    // Get any saved responses for this user and form
    $savedResponses = getSavedResponses($conn, $formId, $userId);
    
    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'fields' => $fields,
        'savedResponses' => $savedResponses
    ]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}
