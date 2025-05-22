<?php

include("../controllers/user_controller.php");


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $user_name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $user_role = $_POST['role'];

    // Call the registerUser method in GeneralController
    $registrationResult = registerUser_ctr($user_name, $email, $password, $user_role);

    // Check the result and handle accordingly
    if ($registrationResult !== false) {
        // Registration successful
        header("Location: ../view/admin/users.php");
    } else {
        // Registration failed
        $error_message = "Registration failed. ";
        echo $error_message . "Handle errors or redirect as needed.";
    }
}
?>
