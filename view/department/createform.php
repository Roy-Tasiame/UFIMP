<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ashesi Form Builder</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="nav.css">
    <style>
        .container {
            max-width: 850px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-left: 21.5rem;
        }

        .header {
            background-color: var(--maroon);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 400;
        }

        .form-container {
            padding: 20px;
        }

        #formDescription,
        #dueDate {
            width: 100%;
            padding: 14px 16px;
            margin-top: 8px;
            margin-bottom: 24px;
            border: 1px solid #dadce0;
            border-radius: 8px;
            background-color: #fff;
            font-size: 15px;
            font-family: "Segoe UI", "Roboto", "Helvetica Neue", sans-serif;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 2px rgba(60, 64, 67, 0.08);
        }

        #formDescription:focus,
        #dueDate:focus {
            border-color: var(--maroon);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            outline: none;
        }


        .form-title-input {
            width: 100%;
            padding: 15px;
            font-size: 28px;
            border: none;
            border-bottom: 2px solid transparent;
            margin-bottom: 20px;
            transition: all 0.3s;
            background-color: white;
        }

        .form-title-input:focus {
            outline: none;
            border-bottom-color: var(--maroon);
        }

        .field-container {
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s;
            position: relative;
        }

        .field-container:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .field-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .field-label-input {
            font-size: 16px;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            width: 70%;
        }

        .field-type-select {
            width: 200px;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background-color: white;
            margin-top: 10px;
        }

        .field-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .required-toggle {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--dark);
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-primary {
            background-color: var(--maroon);
            color: white;
        }

        .btn-secondary {
            background-color: white;
            color: var(--maroon);
            border: 1px solid var(--maroon);
        }

        .btn-danger {
            background-color: white;
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .options-container {
            margin-top: 15px;
        }

        .option-row {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
            align-items: center;
        }

        .option-input {
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            flex-grow: 1;
        }

        .add-field-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: var(--maroon);
            color: white;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s;
        }

        .add-field-button:hover {
            transform: scale(1.1);
        }

        .preview-toggle {
            background-color: white;
            color: var(--maroon);
            border: 1px solid var(--maroon);
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        .drag-handle {
            cursor: move;
            color: #999;
            margin-right: 10px;
        }

        .field-preview {
            margin-top: 15px;
            padding: 10px;
            background-color: var(--light-gray);
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../assets/img/logonobg.png" alt="Ashesi Logo" class="sidebar-logo" style = "margin-top: 25px;">
            <h2>Ashesi Forms</h2>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="admindashboard.php" class="nav-link active">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="myforms.php" class="nav-link">
                    <i class="fas fa-file-alt"></i>
                    My Forms
                </a>
            </div>
            <div class="nav-item">
                <a href="analytics.php" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    Analytics
                </a>
            </div>
            <div class="nav-item">
                <a href="settings.php" class="nav-link">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>
            </div>
        </nav>
    </div>
    <div class="container">
        <div class="header">
            <h1>Ashesi Form Builder</h1>
            <div>
                <button class="preview-toggle" onclick="togglePreview()">
                    <i class="fas fa-eye"></i> Preview
                </button>
                <button class="btn btn-secondary" onclick="saveForm()">
                    <i class="fas fa-save"></i> Save
                </button>
            </div>
        </div>

        <div class="form-container">
            <input type="text" class="form-title-input" id="formTitle" placeholder="Enter form title" />

            <label for="formDescription" style="display:block; margin-top: 15px;">Description:</label>
            <textarea id="formDescription" placeholder="Enter form description"  name = "description"
                    style="padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; width: 100%; height: 100px;"></textarea>

            <label for="dueDate" style="display:block; margin-top: 15px;">Due Date:</label>
            <input type="datetime-local" id="dueDate" name = "due_date"
                style="padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; width: 100%;" />

            <div id="formPreview"></div>
        </div>

    </div>

    <div class="add-field-button" onclick="addField()">
        <i class="fas fa-plus"></i>
    </div>

    <script>
        let fieldCounter = 0;
        const formPreview = document.getElementById('formPreview');

        function addField() {
            const fieldId = `field_${fieldCounter++}`;
            const fieldHtml = `
                <div class="field-container" id="${fieldId}">
                    <div class="field-header">
                        <i class="fas fa-grip-vertical drag-handle"></i>
                        <input type="text" class="field-label-input" placeholder="Question" onchange="updateFieldPreview('${fieldId}')" />
                        <div class="field-actions">
                            <div class="required-toggle">
                                <input type="checkbox" id="required_${fieldId}" class="required-checkbox" onchange="updateFieldPreview('${fieldId}')" />
                                <label for="required_${fieldId}">Required</label>
                            </div>
                            <button class="btn btn-danger" onclick="removeField('${fieldId}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <select class="field-type-select" onchange="updateFieldPreview('${fieldId}')">
                        <option value="text">Short Answer</option>
                        <option value="textarea">Long Answer</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="radio">Choice</option>
                        <option value="checkbox">Multiple Choice</option>
                        <option value="select">Dropdown</option>
                        <option value="file">File Upload</option>
                        <option value="rating">Rating</option>
                    </select>

                    <div class="field-options"></div>
                    <div class="field-preview"></div>
                </div>
            `;
            formPreview.insertAdjacentHTML('beforeend', fieldHtml);
            updateFieldPreview(fieldId);
        }

        function updateFieldPreview(fieldId) {
            const container = document.getElementById(fieldId);
            const fieldType = container.querySelector('.field-type-select').value;
            const label = container.querySelector('.field-label-input').value || 'Untitled Question';
            const required = container.querySelector('.required-checkbox').checked;
            const optionsContainer = container.querySelector('.field-options');
            const previewContainer = container.querySelector('.field-preview');

            // Clear existing options
            optionsContainer.innerHTML = '';

            // Add options interface for radio, checkbox, and select types
            if (['radio', 'checkbox', 'select'].includes(fieldType)) {
                optionsContainer.innerHTML = `
                    <div class="options-container">
                        <button class="btn btn-secondary" onclick="addOption('${fieldId}')">
                            <i class="fas fa-plus"></i> Add Option
                        </button>
                        <div class="options-list"></div>
                    </div>
                `;
                addOption(fieldId);
            }

            // Show preview
            let previewHtml = `<strong>${label}</strong>${required ? ' <span style="color: red">*</span>' : ''}<br>`;
            
            switch(fieldType) {
                case 'text':
                    previewHtml += '<input type="text" disabled placeholder="Short answer text" style="width: 100%; padding: 8px; margin-top: 5px;">';
                    break;
                case 'textarea':
                    previewHtml += '<textarea disabled placeholder="Long answer text" style="width: 100%; padding: 8px; margin-top: 5px; height: 100px;"></textarea>';
                    break;
                case 'rating':
                    previewHtml += '<div style="margin-top: 10px;">⭐⭐⭐⭐⭐</div>';
                    break;
                // Add more preview types as needed
            }

            previewContainer.innerHTML = previewHtml;
        }

        function addOption(fieldId) {
            const container = document.getElementById(fieldId);
            const optionsList = container.querySelector('.options-list');
            const optionHtml = `
                <div class="option-row">
                    <i class="fas fa-grip-vertical drag-handle"></i>
                    <input type="text" class="option-input" placeholder="Option text" />
                    <button class="btn btn-danger" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            optionsList.insertAdjacentHTML('beforeend', optionHtml);
        }

        function removeField(fieldId) {
            document.getElementById(fieldId).remove();
        }

        function togglePreview() {
            // Toggle between edit and preview mode
            alert('Preview mode - Implementation pending');
        }

        // save_form.js
        function saveForm() {
            const formData = {
                title: document.getElementById('formTitle').value,
                due_date: document.getElementById('dueDate').value,
                fields: []

            };
            
            document.querySelectorAll('.field-container').forEach(field => {
                const fieldData = {
                    label: field.querySelector('.field-label-input').value,
                    type: field.querySelector('.field-type-select').value,
                    required: field.querySelector('.required-checkbox').checked,
                    options: []
                };
                
                if (['radio', 'checkbox', 'select'].includes(fieldData.type)) {
                    field.querySelectorAll('.option-input').forEach(option => {
                        if (option.value) {
                            fieldData.options.push(option.value);
                        }
                    });
                }
                formData.fields.push(fieldData);
            });

            fetch('../../actions/save_form.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('Server returned invalid JSON: ' + text);
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    alert('Form saved successfully!');
                    window.location.href = `preview.php?id=${data.formId}`;
                } else {
                    alert('Error saving form: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error details:', error);
                alert('Error saving form: ' + error.message);
            });
        }
    </script>
</body>
</html>