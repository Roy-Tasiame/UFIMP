<?php
include(__DIR__ . '/../settings/config.php'); // your database connection file


function getAllFormsByUser(mysqli $conn, int $userId) {
    $sql = "
        SELECT forms.*, users.name AS user_name
        FROM forms
        JOIN users ON forms.user_id = users.user_id
        WHERE forms.user_id = ?
        ORDER BY forms.updated_at DESC
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $forms = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $forms[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
    return $forms;
}

function countTotalResponsesByUser(mysqli $conn, int $userId): int {
    global $conn;
    $sql = "
        SELECT COUNT(*) AS total 
        FROM form_responses 
        WHERE form_id IN (
            SELECT form_id FROM forms WHERE user_id = ?
        )
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $total;
}



function countRecentFormResponsesByUser(mysqli $conn, int $userId, int $days = 7): int {
    $sql = "
        SELECT COUNT(*) as total 
        FROM form_responses 
        WHERE created_at >= NOW() - INTERVAL ? DAY 
        AND form_id IN (
            SELECT form_id FROM forms WHERE user_id = ?
        )
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $days, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $total;
}


function countNewFormsByUser(mysqli $conn, int $userId, int $days): int {
    $sql = "SELECT COUNT(*) as total 
            FROM forms 
            WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $days);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $total;
}





?>