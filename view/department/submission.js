// Global variables
let currentPage = 1;
let itemsPerPage = 10;
let currentSubmissionId = null;
let submissions = []; // This would be populated from PHP in a real implementation

// Initialize when the document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Set up event listeners
    setupEventListeners();
    
    // Check for checkboxes to enable/disable bulk delete button
    updateBulkDeleteButton();
});

// Set up event listeners
function setupEventListeners() {
    // Select all checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', updateBulkDeleteButton);
    }
    
    // Individual checkboxes
    const checkboxes = document.querySelectorAll('.submission-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteButton);
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('submissionModal');
        if (event.target === modal) {
            closeModal();
        }
    });
}

// Toggle select all checkboxes
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.submission-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateBulkDeleteButton();
}

// Update bulk delete button state
function updateBulkDeleteButton() {
    const checkboxes = document.querySelectorAll('.submission-checkbox:checked');
    const bulkDeleteButton = document.getElementById('bulkDelete');
    
    if (bulkDeleteButton) {
        bulkDeleteButton.disabled = checkboxes.length === 0;
    }
}

// Filter submissions by form
function filterSubmissions() {
    const formSelector = document.getElementById('formSelector');
    const formId = formSelector.value;
    
    // Redirect to the same page with form_id parameter
    window.location.href = 'viewsubmissions.php' + (formId ? '?form_id=' + formId : '');
}

// Search submissions
function searchSubmissions() {
    const searchInput = document.getElementById('searchInput');
    const searchTerm = searchInput.value.toLowerCase();
    const tableRows = document.querySelectorAll('#submissionsTableBody tr');
    
    tableRows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        if (rowText.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Change page in pagination
function changePage(direction) {
    // This would need to be implemented with server-side pagination
    // For now, we just update the page number display
    const currentPageSpan = document.getElementById('currentPage');
    const totalPagesSpan = document.getElementById('totalPages');
    
    if (currentPageSpan && totalPagesSpan) {
        const totalPages = parseInt(totalPagesSpan.textContent);
        let newPage = parseInt(currentPageSpan.textContent) + direction;
        
        if (newPage < 1) newPage = 1;
        if (newPage > totalPages) newPage = totalPages;
        
        currentPageSpan.textContent = newPage;
        currentPage = newPage;
        
        // In a real implementation, this would fetch the new page data from the server
        // loadSubmissions(currentPage);
    }
}

// Refresh the submissions table
function refreshTable() {
    // In a real implementation, this would fetch fresh data from the server
    // For now, just show a loading state and reset after a short delay
    const tableBody = document.getElementById('submissionsTableBody');
    
    if (tableBody) {
        const originalContent = tableBody.innerHTML;
        tableBody.innerHTML = '<tr><td colspan="8" class="no-data">Loading submissions...</td></tr>';
        
        setTimeout(() => {
            tableBody.innerHTML = originalContent;
        }, 1000);
    }
}

// View submission details
function viewSubmission(submissionId) {
    // In a real implementation, this would fetch the submission details from the server
    currentSubmissionId = submissionId;
    
    // For demo purposes, generate some dummy content
    const submissionDetails = document.getElementById('submissionDetails');
    if (submissionDetails) {
        // Create content for the modal
        submissionDetails.innerHTML = `
            <div class="submission-header">
                <h3>Submission #${submissionId}</h3>
                <span class="status-badge completed">Completed</span>
            </div>
            
            <table class="submission-detail-table">
                <tr>
                    <th>Form Name</th>
                    <td>Employee Feedback Form</td>
                </tr>
                <tr>
                    <th>Submitted By</th>
                    <td>John Doe</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>john.doe@example.com</td>
                </tr>
                <tr>
                    <th>Submission Date</th>
                    <td>May 5, 2023 14:30</td>
                </tr>
                <tr>
                    <th>IP Address</th>
                    <td>192.168.1.1</td>
                </tr>
            </table>
            
            <div class="form-responses">
                <h3>Form Responses</h3>
                
                <div class="response-item">
                    <div class="response-question">How would you rate your overall satisfaction?</div>
                    <div class="response-answer">Very Satisfied</div>
                </div>
                
                <div class="response-item">
                    <div class="response-question">What aspects of the company culture do you enjoy most?</div>
                    <div class="response-answer">The collaborative environment and work-life balance policies are excellent. I also appreciate the learning opportunities provided by the company.</div>
                </div>
                
                <div class="response-item">
                    <div class="response-question">Do you have any suggestions for improvement?</div>
                    <div class="response-answer">I think we could benefit from more cross-department collaboration and knowledge sharing sessions.</div>
                </div>
            </div>
        `;
    }
    
    // Show the modal
    const modal = document.getElementById('submissionModal');
    if (modal) {
        modal.style.display = 'block';
    }
}

// Close the modal
function closeModal() {
    const modal = document.getElementById('submissionModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Download submission
function downloadSubmission(submissionId) {
    // In a real implementation, this would generate and download a file
    alert(`Downloading submission #${submissionId}...`);
}

// Download current submission (from modal)
function downloadCurrentSubmission() {
    if (currentSubmissionId) {
        downloadSubmission(currentSubmissionId);
    }
}

// Delete submission
function deleteSubmission(submissionId) {
    // Confirm deletion
    if (confirm(`Are you sure you want to delete submission #${submissionId}?`)) {
        // In a real implementation, this would send a delete request to the server
        alert(`Submission #${submissionId} deleted successfully.`);
        
        // Remove the row from the table
        const row = document.querySelector(`tr input[value="${submissionId}"]`).closest('tr');
        if (row) {
            row.remove();
        }
    }
}

// Export submissions to CSV
function exportToCSV() {
    // In a real implementation, this would generate and download a CSV file
    alert('Exporting submissions to CSV...');
}

// This function would be called in a real implementation to load submissions from the server
function loadSubmissions(page) {
    // Example AJAX request
    /* 
    fetch('get_submissions.php?page=' + page)
        .then(response => response.json())
        .then(data => {
            // Update the table with new data
            updateSubmissionsTable(data.submissions);
            
            // Update pagination info
            document.getElementById('currentPage').textContent = data.current_page;
            document.getElementById('totalPages').textContent = data.total_pages;
        })
        .catch(error => {
            console.error('Error loading submissions:', error);
        });
    */
}