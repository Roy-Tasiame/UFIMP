<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

try {
    $formId = (int)$_POST['form_id'];
    
    // Start transaction
    $mysqli->begin_transaction();

    // Insert response
    $stmt = $mysqli->prepare("
        INSERT INTO form_responses (
            form_id,
            created_at,
            completed_at
        ) VALUES (?, NOW(), NOW())
    ");

    $stmt->bind_param("i", $formId);
    $stmt->execute();
    $responseId = $mysqli->insert_id;
    $stmt->close();

    // Insert response values
    $valueStmt = $mysqli->prepare("
        INSERT INTO response_values (
            response_id,
            field_id,
            value,
            created_at
        ) VALUES (?, ?, ?, NOW())
    ");

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'field_') === 0) {
            $fieldId = (int)substr($key, 6);
            
            // Handle array values (checkboxes)
            if (is_array($value)) {
                $value = implode(', ', $value);
            }

            $valueStmt->bind_param("iis", $responseId, $fieldId, $value);
            $valueStmt->execute();
        }
    }

    $valueStmt->close();

    // Commit transaction
    $mysqli->commit();

    // Redirect to thank you page
    header('Location: ../view/admin/thank_you.php?form_id=' . $formId);
    exit;

} catch (Exception $e) {
    // Rollback transaction on error
    $mysqli->rollback();
    die('Error submitting form: ' . $e->getMessage());
}

$mysqli->close();
?>