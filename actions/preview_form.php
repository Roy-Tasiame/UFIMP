<?php
require_once 'config.php';

$formId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$formId) {
    die('Form ID is required');
}

try {
    // Get form details
    $stmt = $pdo->prepare("
        SELECT * FROM forms 
        WHERE id = :form_id
    ");
    $stmt->execute([':form_id' => $formId]);
    $form = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$form) {
        die('Form not found');
    }

    // Get form fields
    $fieldStmt = $pdo->prepare("
        SELECT * FROM form_fields 
        WHERE form_id = :form_id 
        ORDER BY order_position
    ");
    $fieldStmt->execute([':form_id' => $formId]);
    $fields = $fieldStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get options for fields that have them
    $optionStmt = $pdo->prepare("
        SELECT * FROM field_options 
        WHERE field_id = :field_id 
        ORDER BY order_position
    ");

    // Add options to their respective fields
    foreach ($fields as &$field) {
        if (in_array($field['type'], ['radio', 'checkbox', 'select'])) {
            $optionStmt->execute([':field_id' => $field['id']]);
            $field['options'] = $optionStmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

} catch (Exception $e) {
    die('Error loading form: ' . $e->getMessage());
}
?>