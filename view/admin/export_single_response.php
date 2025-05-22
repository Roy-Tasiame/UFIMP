<?php
// Initialize session if not already started
session_start();

// Database connection
require_once('../../settings/config.php'); // Adjust to your configuration file path

// Authentication check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Check if response_id is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid response ID");
}

$response_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Get response information and verify permissions
$stmt = $conn->prepare("SELECT fr.id, fr.form_id, fr.created_at, f.title, 
                         COALESCE(u.name, 'Anonymous') as respondent_name,
                         f.user_id as form_creator, admin.role as user_role
                      FROM form_responses fr 
                      INNER JOIN forms f ON fr.form_id = f.id
                      LEFT JOIN users u ON fr.user_id = u.user_id 
                      INNER JOIN users admin ON admin.user_id = ?
                      WHERE fr.id = ?");
$stmt->bind_param("ii", $user_id, $response_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Response not found");
}

$response_data = $result->fetch_assoc();

// Check if user has permission (form creator or admin)
if ($response_data['form_creator'] != $user_id && $response_data['user_role'] != 'admin') {
    die("You don't have permission to export this response");
}

$form_id = $response_data['form_id'];
$form_title = $response_data['title'];
$respondent_name = $response_data['respondent_name'];
$submission_date = $response_data['created_at'];

// Get all fields for this form
$fields = [];
$stmt = $conn->prepare("SELECT id, label, type FROM form_fields WHERE form_id = ? ORDER BY order_position");
$stmt->bind_param("i", $form_id);
$stmt->execute();
$fields_result = $stmt->get_result();

while ($field = $fields_result->fetch_assoc()) {
    $fields[$field['id']] = $field;
}

// Get all values for this response
$field_values = [];
$stmt = $conn->prepare("SELECT field_id, value FROM response_values WHERE response_id = ?");
$stmt->bind_param("i", $response_id);
$stmt->execute();
$values_result = $stmt->get_result();

while ($value = $values_result->fetch_assoc()) {
    $field_values[$value['field_id']] = $value['value'];
}

// Prepare CSV filename
$sanitized_title = preg_replace('/[^a-z0-9]+/', '-', strtolower($form_title));
$filename = $sanitized_title . '-response-' . $response_id . '-' . date('Y-m-d') . '.csv';

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Create output stream
$output = fopen('php://output', 'w');

// Output response metadata
fputcsv($output, ['Form Title', $form_title]);
fputcsv($output, ['Response ID', $response_id]);
fputcsv($output, ['Submission Date', $submission_date]);
fputcsv($output, ['Respondent', $respondent_name]);
fputcsv($output, []); // Empty row as separator

// Output column headers for response details
fputcsv($output, ['Question', 'Answer']);

// Output each field and its value
foreach ($fields as $field_id => $field) {
    $value = isset($field_values[$field_id]) ? $field_values[$field_id] : '';
    
    // Handle special field types if needed
    if (in_array($field['type'], ['checkbox', 'select-multiple']) && !empty($value)) {
        // Format if needed
    }
    
    fputcsv($output, [$field['label'], $value]);
}

// Close the output stream
fclose($output);
exit;
?>