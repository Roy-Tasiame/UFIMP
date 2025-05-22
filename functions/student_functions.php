<?php
include(__DIR__ . '/../settings/config.php'); // your database connection file


function countAllResponsesByStudent($conn, $studentId) {
    // SQL query to count all responses by the student
    $sql = "SELECT COUNT(*) FROM form_responses WHERE user_id = ?";
    
    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the studentId parameter to the prepared statement
        $stmt->bind_param("i", $studentId);
        
        // Execute the query
        $stmt->execute();
        
        // Bind the result to a variable
        $stmt->bind_result($responseCount);
        
        // Fetch the result
        $stmt->fetch();
        
        // Close the statement
        $stmt->close();
        
        return $responseCount;
    } else {
        // Handle error if the prepare statement fails
        return null;
    }
}


function countRecentResponsesByStudent($conn, $studentId, $daysRange) {
    // SQL query to count recent responses
    $sql = "SELECT COUNT(*) FROM form_responses 
            WHERE user_id = ? 
            AND created_at >= NOW() - INTERVAL ? DAY";
    
    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the studentId and daysRange parameters to the prepared statement
        $stmt->bind_param("ii", $studentId, $daysRange);
        
        // Execute the query
        $stmt->execute();
        
        // Bind the result to a variable
        $stmt->bind_result($recentResponseCount);
        
        // Fetch the result
        $stmt->fetch();
        
        // Close the statement
        $stmt->close();
        
        return $recentResponseCount;
    } else {
        // Handle error if the prepare statement fails
        return null;
    }
}


function getLastResponseDate($conn, $studentId) {
    // SQL query to get the latest response date
    $sql = "SELECT DATE(MAX(created_at)) FROM form_responses WHERE user_id = ?";
    
    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the studentId parameter to the prepared statement
        $stmt->bind_param("i", $studentId);
        
        // Execute the query
        $stmt->execute();
        
        // Bind the result to a variable
        $stmt->bind_result($lastResponseDate);
        
        // Fetch the result
        $stmt->fetch();
        
        // Close the statement
        $stmt->close();
        
        // Return the last response date or null if no response found
        return $lastResponseDate ? $lastResponseDate : null;
    } else {
        // Handle error if the prepare statement fails
        return null;
    }
}



function getIncompleteResponses($conn, $studentId) {
    // SQL query to count incomplete responses by the student
    $sql = "SELECT COUNT(*) FROM form_responses WHERE user_id = ? AND is_complete = 0";
    
    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the studentId parameter to the prepared statement
        $stmt->bind_param("i", $studentId);
        
        // Execute the query
        $stmt->execute();
        
        // Bind the result to a variable
        $stmt->bind_result($incompleteCount);
        
        // Fetch the result
        $stmt->fetch();
        
        // Close the statement
        $stmt->close();
        
        return $incompleteCount;
    } else {
        // Handle error if the prepare statement fails
        return null;
    }
}


function getFormsNotYetFilled($conn, $studentId) {
    // SQL query to count forms the student has not yet filled
    $sql = "SELECT COUNT(DISTINCT f.id)
            FROM forms f
            LEFT JOIN form_responses fr ON f.id = fr.form_id AND fr.user_id = ?
            WHERE fr.id IS NULL OR fr.is_complete = 0";
    
    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the studentId parameter to the prepared statement
        $stmt->bind_param("i", $studentId);
        
        // Execute the query
        $stmt->execute();
        
        // Bind the result to a variable
        $stmt->bind_result($formsNotFilledCount);
        
        // Fetch the result
        $stmt->fetch();
        
        // Close the statement
        $stmt->close();
        
        return $formsNotFilledCount;
    } else {
        // Handle error if the prepare statement fails
        return null;
    }
}



function getFormResponseHistory($conn, $studentId) {
    // SQL query to fetch form response history with department information from the user role
    $sql = "
        SELECT 
            f.title AS form_title,
            u.role AS department,  -- Role is being used to indicate department here
            fr.created_at AS date_submitted,
            IF(fr.is_complete = 1, 'Completed', 'Incomplete') AS status,
            fr.id AS form_response_id
        FROM 
            form_responses fr
        JOIN 
            forms f ON f.id = fr.form_id
        LEFT JOIN 
            users u ON u.user_id = f.user_id  -- Assuming that user’s role is tied to department
        WHERE 
            fr.user_id = ? 
        ORDER BY 
            fr.created_at DESC;
    ";
    
    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the studentId parameter to the prepared statement
        $stmt->bind_param("i", $studentId);
        
        // Execute the query
        $stmt->execute();
        
        // Bind the result to variables
        $stmt->bind_result($formTitle, $department, $dateSubmitted, $status, $formResponseId);
        
        // Create an array to store the results
        $responses = [];
        
        // Fetch the results and store them in the array
        while ($stmt->fetch()) {
            // Format the date to show only the date (without time)
            $formattedDateSubmitted = date("Y-m-d", strtotime($dateSubmitted));

            // Add each response to the array
            $responses[] = [
                'form_title' => $formTitle,
                'department' => $department,
                'date_submitted' => $formattedDateSubmitted,
                'status' => $status,
                'form_response_id' => $formResponseId
            ];
        }
        
        // Close the statement
        $stmt->close();
        
        // Return the array of responses
        return $responses;
    } else {
        // Handle error if the prepare statement fails
        return null;
    }
}
?>


