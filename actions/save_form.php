<?php
header('Content-Type: application/json');
require_once 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        throw new Exception('Invalid JSON data');
    }

    $title = $data['title'];
    $description = isset($data['description']) ? $data['description'] : null;
    $due_date = !empty($data['due_date']) ? $data['due_date'] : null;

    // Start transaction
    $mysqli->begin_transaction();

    // Insert form with description and due_date
    $stmt = $mysqli->prepare("
        INSERT INTO forms (title, description, created_at, updated_at, due_date, user_id) 
        VALUES (?, ?, NOW(), NOW(), ?, ?)
    ");

    $stmt->bind_param("sssi", $title, $description, $due_date, $user_id);
    $stmt->execute();
    $formId = $stmt->insert_id;
    $stmt->close();

    // Prepare statements for fields and options
    $fieldStmt = $mysqli->prepare("
        INSERT INTO form_fields (
            form_id, 
            label, 
            type, 
            required,
            order_position,
            created_at, 
            updated_at
        ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())
    ");

    $optionStmt = $mysqli->prepare("
        INSERT INTO field_options (
            field_id,
            option_text,
            order_position,
            created_at,
            updated_at
        ) VALUES (?, ?, ?, NOW(), NOW())
    ");

    foreach ($data['fields'] as $index => $field) {
        $required = $field['required'] ? 1 : 0;
        $fieldStmt->bind_param("issii", $formId, $field['label'], $field['type'], $required, $index);
        $fieldStmt->execute();
        $fieldId = $fieldStmt->insert_id;

        // Insert options if they exist
        if (isset($field['options']) && is_array($field['options'])) {
            foreach ($field['options'] as $optionIndex => $optionText) {
                $optionStmt->bind_param("isi", $fieldId, $optionText, $optionIndex);
                $optionStmt->execute();
            }
        }
    }

    $fieldStmt->close();
    $optionStmt->close();

    // Commit transaction
    $mysqli->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Form saved successfully',
        'formId' => $formId
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $mysqli->rollback();

    echo json_encode([
        'success' => false,
        'message' => 'Error saving form: ' . $e->getMessage()
    ]);
}

$mysqli->close();
?>
