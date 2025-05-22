<?php
/**
 * This file contains helper functions for handling forms, submissions and form fields using MySQLi
 */


function checkFormSubmissionStatus($conn, $formId, $userId) {
    $stmt = $conn->prepare("SELECT * FROM form_responses WHERE form_id = ? AND user_id = ? AND is_complete = 1");
    $stmt->bind_param("ii", $formId, $userId);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        return 'completed';
    }
    $stmt->close();

    $stmt = $conn->prepare("SELECT * FROM form_responses WHERE form_id = ? AND user_id = ? AND is_complete = 0");
    $stmt->bind_param("ii", $formId, $userId);
    $stmt->execute();
    $stmt->store_result();
    $status = $stmt->num_rows > 0 ? 'pending' : 'new';
    $stmt->close();

    return $status;
}

function getFormFields($conn, $formId) {
    $fields = [];
    $stmt = $conn->prepare("SELECT * FROM form_fields WHERE form_id = ? ORDER BY order_position ASC");
    $stmt->bind_param("i", $formId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($field = $result->fetch_assoc()) {
        $fields[] = $field;
    }
    $stmt->close();

    foreach ($fields as &$field) {
        if (in_array($field['type'], ['radio', 'checkbox', 'select'])) {
            $stmt = $conn->prepare("SELECT * FROM field_options WHERE field_id = ? ORDER BY order_position ASC");
            $stmt->bind_param("i", $field['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $options = [];
            while ($option = $result->fetch_assoc()) {
                $options[] = $option;
            }
            $field['options'] = $options;
            $stmt->close();
        }
    }

    return $fields;
}

function getSavedResponses($conn, $formId, $userId) {
    $stmt = $conn->prepare("SELECT id FROM form_responses WHERE form_id = ? AND user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("ii", $formId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $response = $result->fetch_assoc();
    $stmt->close();

    if (!$response) {
        return [];
    }

    $responseId = $response['id'];
    $stmt = $conn->prepare("SELECT rv.field_id, rv.value FROM response_values rv WHERE rv.response_id = ?");
    $stmt->bind_param("i", $responseId);
    $stmt->execute();
    $result = $stmt->get_result();

    $values = [];
    while ($row = $result->fetch_assoc()) {
        $val = $row['value'];
        $values[$row['field_id']] = (strpos($val, '[') === 0) ? json_decode($val, true) : $val;
    }

    $stmt->close();
    return $values;
}

function saveFormResponse($conn, $formId, $userId, $formData, $isComplete = false) {
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("SELECT id FROM form_responses WHERE form_id = ? AND user_id = ? AND is_complete = 0");
        $stmt->bind_param("ii", $formId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingResponse = $result->fetch_assoc();
        $stmt->close();

        if ($existingResponse) {
            $responseId = $existingResponse['id'];
            $stmt = $conn->prepare("DELETE FROM response_values WHERE response_id = ?");
            $stmt->bind_param("i", $responseId);
            $stmt->execute();
            $stmt->close();

            if ($isComplete) {
                $stmt = $conn->prepare("UPDATE form_responses SET is_complete = 1 WHERE id = ?");
                $stmt->bind_param("i", $responseId);
                $stmt->execute();
                $stmt->close();
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO form_responses (form_id, user_id, is_complete, created_at) VALUES (?, ?, ?, NOW())");
            $completeFlag = $isComplete ? 1 : 0;
            $stmt->bind_param("iii", $formId, $userId, $completeFlag);
            $stmt->execute();
            $responseId = $stmt->insert_id;
            $stmt->close();
        }

        foreach ($formData as $fieldId => $value) {
            if (!is_numeric($fieldId) && strpos($fieldId, 'field-') !== 0) {
                continue;
            }
            if (strpos($fieldId, 'field-') === 0) {
                $fieldId = substr($fieldId, 6);
            }
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $stmt = $conn->prepare("INSERT INTO response_values (response_id, field_id, value, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iis", $responseId, $fieldId, $value);
            $stmt->execute();
            $stmt->close();
        }

        $conn->commit();
        return [
            'success' => true,
            'message' => $isComplete ? 'Form submitted successfully!' : 'Progress saved successfully!',
            'response_id' => $responseId
        ];
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Database error in saveFormResponse: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ];
    }
}
