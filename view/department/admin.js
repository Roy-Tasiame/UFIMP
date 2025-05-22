document.addEventListener('DOMContentLoaded', function() {
    // Department Modal Functionality
    const deptModal = document.getElementById('deptModal');
    const addDeptBtn = document.getElementById('addDeptBtn');
    const deptCloseBtn = deptModal?.querySelector('.close');
    
    if (addDeptBtn && deptModal) {
        addDeptBtn.addEventListener('click', function() {
            deptModal.style.display = 'block';
        });
        
        deptCloseBtn.addEventListener('click', function() {
            deptModal.style.display = 'none';
        });
        
        window.addEventListener('click', function(event) {
            if (event.target === deptModal) {
                deptModal.style.display = 'none';
            }
        });
    }
    
    // User Modal Functionality
    const userModal = document.getElementById('userModal');
    const addUserBtn = document.getElementById('addUserBtn');
    const userCloseBtn = userModal?.querySelector('.close');
    
    if (addUserBtn && userModal) {
        addUserBtn.addEventListener('click', function() {
            userModal.style.display = 'block';
        });
        
        userCloseBtn.addEventListener('click', function() {
            userModal.style.display = 'none';
        });
        
        window.addEventListener('click', function(event) {
            if (event.target === userModal) {
                userModal.style.display = 'none';
            }
        });
        
        // Show/hide fields based on selected role
        const userRoleSelect = document.getElementById('userRole');
        const studentFields = document.getElementById('studentFields');
        const facultyFields = document.getElementById('facultyFields');
        
        if (userRoleSelect && studentFields && facultyFields) {
            // Hide both by default
            studentFields.style.display = 'none';
            facultyFields.style.display = 'none';
            
            userRoleSelect.addEventListener('change', function() {
                const selectedRole = this.value;
                
                if (selectedRole === 'student') {
                    studentFields.style.display = 'block';
                    facultyFields.style.display = 'none';
                } else if (selectedRole === 'department') {
                    studentFields.style.display = 'none';
                    facultyFields.style.display = 'block';
                } else {
                    studentFields.style.display = 'none';
                    facultyFields.style.display = 'none';
                }
            });
        }
    }
    
    // Department Search Functionality
    const deptSearch = document.getElementById('deptSearch');
    if (deptSearch) {
        deptSearch.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const deptTable = document.querySelector('.data-table');
            const deptRows = deptTable.querySelectorAll('tbody tr');
            
            deptRows.forEach(row => {
                const deptName = row.cells[0].textContent.toLowerCase();
                const deptCode = row.cells[1].textContent.toLowerCase();
                const deptHead = row.cells[2].textContent.toLowerCase();
                
                if (deptName.includes(searchTerm) || deptCode.includes(searchTerm) || deptHead.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // User Search & Filter Functionality
    const userSearch = document.getElementById('userSearch');
    const roleFilter = document.getElementById('roleFilter');
    const deptFilter = document.getElementById('deptFilter');
    
    function filterUsers() {
        const searchTerm = userSearch ? userSearch.value.toLowerCase() : '';
        const roleValue = roleFilter ? roleFilter.value : 'all';
        const deptValue = deptFilter ? deptFilter.value : 'all';
        
        const userTable = document.querySelector('.data-table');
        if (!userTable) return;
        
        const userRows = userTable.querySelectorAll('tbody tr');
        
        userRows.forEach(row => {
            const userName = row.cells[0].textContent.toLowerCase();
            const userEmail = row.cells[1].textContent.toLowerCase();
            const userRole = row.cells[2].textContent.toLowerCase();
            const userDept = row.cells[3].textContent.toLowerCase();
            
            // Check if matches search term
            const matchesSearch = searchTerm === '' || 
                userName.includes(searchTerm) || 
                userEmail.includes(searchTerm);
            
            // Check if matches role filter
            const matchesRole = roleValue === 'all' || userRole === roleValue;
            
            // Check if matches department filter (simplified, you may need to adjust)
            const matchesDept = deptValue === 'all' || userDept.includes(deptValue);
            
            if (matchesSearch && matchesRole && matchesDept) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Add event listeners for filtering
    if (userSearch) userSearch.addEventListener('keyup', filterUsers);
    if (roleFilter) roleFilter.addEventListener('change', filterUsers);
    if (deptFilter) deptFilter.addEventListener('change', filterUsers);
    
    // Edit Department buttons
    const editDeptBtns = document.querySelectorAll('.edit-btn');
    editDeptBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const deptId = this.getAttribute('data-id');
            // You'll likely want to populate the form with existing data
            // This would typically involve an AJAX request to get the department data
            if (deptModal) {
                deptModal.style.display = 'block';
                // Set form action to update instead of create
                const deptForm = document.getElementById('deptForm');
                if (deptForm) {
                    deptForm.action = `process_department.php?action=update&id=${deptId}`;
                }
            }
        });
    });
    
    // Delete Department buttons
    const deleteDeptBtns = document.querySelectorAll('.delete-btn');
    deleteDeptBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const deptId = this.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this department? This action cannot be undone.')) {
                window.location.href = `process_department.php?action=delete&id=${deptId}`;
            }
        });
    });
    
    // Handle user actions (edit, suspend/activate, delete)
    document.querySelectorAll('.edit-btn, .suspend-btn, .activate-btn, .delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            const action = this.classList.contains('edit-btn') ? 'edit' :
                          this.classList.contains('suspend-btn') ? 'suspend' :
                          this.classList.contains('activate-btn') ? 'activate' : 'delete';
            
            if (action === 'edit') {
                if (userModal) {
                    userModal.style.display = 'block';
                    // Set form action to update instead of create
                    const userForm = document.getElementById('userForm');
                    if (userForm) {
                        userForm.action = `process_user.php?action=update&id=${userId}`;
                        // You would typically load user data via AJAX here
                    }
                }
            } else if (action === 'delete') {
                if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                    window.location.href = `process_user.php?action=delete&id=${userId}`;
                }
            } else {
                // Handle suspend/activate
                const confirmAction = action === 'suspend' ? 'suspend' : 'activate';
                if (confirm(`Are you sure you want to ${confirmAction} this user?`)) {
                    window.location.href = `process_user.php?action=${action}&id=${userId}`;
                }
            }
        });
    });
});