<?php
// Initialize session if not already started
session_start();

// Database connection
require_once('../../settings/config.php'); // Adjust to your configuration file path

// Authentication check - only allow admins or form creators to export data
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Check if form_id is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid form ID");
}

$form_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Check if user has permission to export this form (form creator or admin)
$stmt = $conn->prepare("SELECT f.title, f.description, u.role 
                      FROM forms f 
                      INNER JOIN users u ON u.user_id = ? 
                      WHERE f.id = ? AND (f.user_id = ? OR u.role = 'admin')");
$stmt->bind_param("iii", $user_id, $form_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("You don't have permission to export this form's data");
}

$form_data = $result->fetch_assoc();
$form_title = $form_data['title'];

// Get all fields for this form
$fields = [];
$stmt = $conn->prepare("SELECT id, label, type FROM form_fields WHERE form_id = ? ORDER BY order_position");
$stmt->bind_param("i", $form_id);
$stmt->execute();
$fields_result = $stmt->get_result();

while ($field = $fields_result->fetch_assoc()) {
    $fields[$field['id']] = $field;
}

// Get all responses for this form
$stmt = $conn->prepare("SELECT fr.id, fr.created_at, COALESCE(u.name, 'Anonymous') as respondent_name 
                      FROM form_responses fr 
                      LEFT JOIN users u ON fr.user_id = u.user_id 
                      WHERE fr.form_id = ? 
                      ORDER BY fr.created_at");
$stmt->bind_param("i", $form_id);
$stmt->execute();
$responses_result = $stmt->get_result();

// Prepare CSV filename
$sanitized_title = preg_replace('/[^a-z0-9]+/', '-', strtolower($form_title));
$filename = $sanitized_title . '-responses-' . date('Y-m-d') . '.csv';

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Create output stream
$output = fopen('php://output', 'w');

// Create CSV header row
$header = ['Response ID', 'Submission Date', 'Respondent'];

// Add field labels to header
foreach ($fields as $field) {
    $header[] = $field['label'];
}

// Write header row to CSV
fputcsv($output, $header);

// Process each response
while ($response = $responses_result->fetch_assoc()) {
    $response_id = $response['id'];
    
    // Create a row for this response
    $row = [
        $response_id,
        $response['created_at'],
        $response['respondent_name']
    ];
    
    // Get all field values for this response
    $stmt = $conn->prepare("SELECT field_id, value FROM response_values WHERE response_id = ?");
    $stmt->bind_param("i", $response_id);
    $stmt->execute();
    $values_result = $stmt->get_result();
    
    // Initialize all fields with empty values
    $field_values = array_fill_keys(array_keys($fields), '');
    
    // Fill in the values we have
    while ($value = $values_result->fetch_assoc()) {
        $field_id = $value['field_id'];
        
        // Special handling for checkbox/multiple selection fields
        if (in_array($fields[$field_id]['type'], ['checkbox', 'select-multiple'])) {
            // For checkbox types, the value might be a comma-separated list
            $field_values[$field_id] = $value['value'];
        } else {
            $field_values[$field_id] = $value['value'];
        }
    }
    
    // Add all field values to the row in the correct order
    foreach ($fields as $field_id => $field) {
        $row[] = isset($field_values[$field_id]) ? $field_values[$field_id] : '';
    }
    
    // Write the response row to CSV
    fputcsv($output, $row);
}

// Close the output stream
fclose($output);
exit;
?>