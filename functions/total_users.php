<?php
include(__DIR__ . '/../settings/config.php'); // your database connection file

function getAllUsers(mysqli $conn) {
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    $users = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

function getAllDepartments(mysqli $conn) {
    $sql = "SELECT * FROM users WHERE `role` = 'department'";
    $result = $conn->query($sql);

    $users = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

function countTotalUsers(mysqli $conn) {
    $sql = "SELECT COUNT(*) AS total_users FROM users";
    $result = $conn->query($sql);
    
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total_users'];
    } else {
        return 0;  // Return 0 if the query fails
    }
}

function countUsersByRole(mysqli $conn, $role) {
    $sql = "SELECT COUNT(*) AS total FROM users WHERE role = ?";
    $stmt = $conn->prepare($sql);
    
    // Bind the role parameter and execute the query
    $stmt->bind_param('s', $role);
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;  // Return 0 if the query fails
    }
}


// Function to fetch all user profile details
function getUserProfile($conn, $userId) {
    // SQL query to fetch user profile data
    $sql = "
        SELECT
            u.first_name,
            u.last_name,
            u.email,
            u.phone_number,
            u.major,
            u.student_id,
            u.class_year,
            u.academic_department,
            u.profile_updated,
            u.role
        FROM users u
        WHERE u.user_id = ?
    ";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the userId parameter to the prepared statement
        $stmt->bind_param("i", $userId);
        
        // Execute the query
        $stmt->execute();
        
        // Bind the result to variables
        $stmt->bind_result($firstName, $lastName, $email, $phoneNumber, $major, $studentId, $classYear, $academicDepartment, $profileUpdated, $role);
        
        // Fetch the result
        if ($stmt->fetch()) {
            // Return the profile data as an associative array
            return [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone_number' => $phoneNumber,
                'major' => $major,
                'student_id' => $studentId,
                'class_year' => $classYear,
                'academic_department' => $academicDepartment,
                'profile_updated' => $profileUpdated,
                'role' => $role
            ];
        } else {
            return null; // User not found
        }
        
        // Close the statement
        $stmt->close();
    } else {
        return null; // Query preparation failed
    }
}


?>