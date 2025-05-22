<?php
require_once '../../actions/config.php';

// Sanitize form ID
$formId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$formId) {
    die('Form ID is required');
}

// Create connection

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

try {
    // Get form details
    $stmt = $mysqli->prepare("SELECT * FROM forms WHERE id = ?");
    $stmt->bind_param("i", $formId);
    $stmt->execute();
    $result = $stmt->get_result();
    $form = $result->fetch_assoc();
    $stmt->close();

    if (!$form) {
        die('Form not found');
    }

    // Get form fields
    $fieldStmt = $mysqli->prepare("
        SELECT * FROM form_fields 
        WHERE form_id = ? 
        ORDER BY order_position
    ");
    $fieldStmt->bind_param("i", $formId);
    $fieldStmt->execute();
    $fieldResult = $fieldStmt->get_result();
    $fields = [];
    while ($field = $fieldResult->fetch_assoc()) {
        $fields[] = $field;
    }
    $fieldStmt->close();

    // Get options for fields that have them
    $optionStmt = $mysqli->prepare("
        SELECT * FROM field_options 
        WHERE field_id = ? 
        ORDER BY order_position
    ");

    // Add options to their respective fields
    foreach ($fields as $index => $field) {
        if (in_array($field['type'], ['radio', 'checkbox', 'select'])) {
            $fields[$index]['options'] = []; // Always initialize
    
            $optionStmt->bind_param("i", $field['id']);
            $optionStmt->execute();
            $optionResult = $optionStmt->get_result();
    
            while ($option = $optionResult->fetch_assoc()) {
                $fields[$index]['options'][] = $option;
            }
        }
    }
    
    $optionStmt->close();

    // Close the connection
    $mysqli->close();
} catch (Exception $e) {
    die('Error loading form: ' . $e->getMessage());
}

// var_dump($fields);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($form['title']) ?></title>
    <style>
        :root {
            --ashesi-maroon: #800020;
            --ashesi-dark: #222;
            --light-gray: #f4f4f4;
            --border-color: #e0e0e0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background-color: var(--light-gray);
            color: var(--ashesi-dark);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            color: var(--ashesi-maroon);
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--ashesi-maroon);
        }

        .field-container {
            margin-bottom: 25px;
        }

        .field-label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
        }

        .required {
            color: red;
            margin-left: 4px;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .radio-group,
        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .option-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .submit-btn {
            background-color: var(--ashesi-maroon);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: opacity 0.3s;
        }

        .submit-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="form-title"><?= htmlspecialchars($form['title']) ?></h1>
        <form id="previewForm" method="post" action="../../actions/submit_responses.php">
            <input type="hidden" name="form_id" value="<?= $formId ?>">
            
            <?php foreach ($fields as $field): ?>
    <?php
        if (!isset($field['label'], $field['type'])) continue; // Skip malformed fields
        $fieldLabel = htmlspecialchars($field['label']);
        $fieldRequired = !empty($field['required']) ? 'required' : '';
        $fieldName = "field_" . (int)$field['id'];
    ?>
    <div class="field-container">
        <label class="field-label">
            <?= $fieldLabel ?>
            <?php if ($fieldRequired): ?>
                <span class="required">*</span>
            <?php endif; ?>
        </label>

        <?php switch($field['type']):
            case 'text': ?>
                <input type="text" name="<?= $fieldName ?>" <?= $fieldRequired ?>>
                <?php break;

            case 'textarea': ?>
                <textarea name="<?= $fieldName ?>" <?= $fieldRequired ?>></textarea>
                <?php break;

            case 'number': ?>
                <input type="number" name="<?= $fieldName ?>" <?= $fieldRequired ?>>
                <?php break;

            case 'date': ?>
                <input type="date" name="<?= $fieldName ?>" <?= $fieldRequired ?>>
                <?php break;

            case 'radio': ?>
                <div class="radio-group">
                    <?php foreach ($field['options'] ?? [] as $option): ?>
                        <div class="option-item">
                            <input type="radio" name="<?= $fieldName ?>" value="<?= htmlspecialchars($option['option_text']) ?>" <?= $fieldRequired ?>>
                            <label><?= htmlspecialchars($option['option_text']) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php break;

            case 'checkbox': ?>
                <div class="checkbox-group">
                    <?php foreach ($field['options'] ?? [] as $option): ?>
                        <div class="option-item">
                            <input type="checkbox" name="<?= $fieldName ?>[]" value="<?= htmlspecialchars($option['option_text']) ?>">
                            <label><?= htmlspecialchars($option['option_text']) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php break;

            case 'select': ?>
                <select name="<?= $fieldName ?>" <?= $fieldRequired ?>>
                    <option value="">Select an option</option>
                    <?php foreach ($field['options'] ?? [] as $option): ?>
                        <option value="<?= htmlspecialchars($option['option_text']) ?>"><?= htmlspecialchars($option['option_text']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php break;
        endswitch; ?>
    </div>
<?php endforeach; ?>


            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>


</body>
</html>