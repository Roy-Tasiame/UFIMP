<?php
//connect to database class
require("../settings/db_class.php");

class customer_class extends db_connection
{
	public function registerUser($user_name, $email, $password, $user_role)
    {
        $ndb = new db_connection();
        
        // Escape and sanitize input data
        $user_name = mysqli_real_escape_string($ndb->db_conn(), $user_name);
        $email = mysqli_real_escape_string($ndb->db_conn(), $email);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $user_role = mysqli_real_escape_string($ndb->db_conn(), $user_role);

        
        $sql = "INSERT INTO `users`(`name`, `email`, `password_hash`, `role`) 
        VALUES ('$user_name', '$email', '$password', '$user_role')";
        return $this->db_query($sql);
	}

	
    public function loginUser($email, $password)
    {
        $ndb = new db_connection();
        
        // Escape and sanitize input data
        $email = mysqli_real_escape_string($ndb->db_conn(), $email);
        $password = mysqli_real_escape_string($ndb->db_conn(), $password);
        
        // Retrieve user from database based on email
        $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
        $result = $this->db_fetch_one($sql);
        
        // Check for SQL query execution errors
        if (!$result) {
            // Handle query execution error
            echo "Error: " . mysqli_error($ndb->db_conn());
            return false;
        }
        
        // Check if user exists
        if ($result != null){

            echo "There is a record in db";
            // $user = mysqli_fetch_assoc($result);
            $user = $result;
            // Verify password
            if (password_verify($password, $user['password_hash'])) {
                // Password is correct, return user data
                return $user;
            } else {
                echo "password is wrong";
                // Password is incorrect
                return false;
            }
        } else {
            echo "Not Found";
            return false;
        }
    }
    
}


?>


    