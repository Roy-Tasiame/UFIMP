<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../settings/config.php';


// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Capture the submitted form data
    $userId = $_SESSION['user_id']; // Assuming user ID is stored in session
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];  // You can make this editable if necessary
    $major = $_POST['major'];
    $phoneNumber = $_POST['phone_number'];
    $studentId = $_POST['student_id'];
    $classYear = $_POST['class_year'];
    // Step 1: Check if the profile has already been updated
    $checkSql = "SELECT profile_updated FROM users WHERE user_id = ?";
    if ($stmt = $conn->prepare($checkSql)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($profileUpdated);
        $stmt->fetch();
        $stmt->close();

        // If profile has already been updated, prevent further updates
        if ($profileUpdated == 1) {
            echo "You cannot update your profile information again.";
            exit;  // Stop the update process
        }
    } else {
        echo "Error checking profile status: " . $conn->error;
        exit;
    }

    // Step 2: Proceed with updating the profile if it's the first update
    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, major = ?, phone_number = ?, student_id = ?, class_year = ?, profile_updated = 1 WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ssssssis", $firstName, $lastName, $email, $major, $phoneNumber, $studentId, $classYear, $userId);
        
        // Execute the query
        $stmt->execute();

        // Check if the update query executed successfully
        if ($stmt->affected_rows > 0) {
            echo "Profile updated successfully.";
        } else {
            echo "No changes were made to the profile.";
        }

        // Optionally, update the `name` field if needed (Full name logic)
        $fullName = $firstName . ' ' . $lastName;
        $updateNameSql = "UPDATE users SET name = ? WHERE user_id = ?";
        if ($updateStmt = $conn->prepare($updateNameSql)) {
            $updateStmt->bind_param("si", $fullName, $userId);
            $updateStmt->execute();
            $updateStmt->close();
        } else {
            echo "Error updating full name: " . $conn->error;
        }

        $stmt->close();

        // Redirect to the profile page after successful update
        header("Location: ../view/student/profile.php");
        exit;
    } else {
        echo "Error updating profile: " . $conn->error;
        exit;
    }

} else {
    echo "Invalid request method.";
    exit;
}
?>
