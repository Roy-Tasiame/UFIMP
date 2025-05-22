<?php

include(__DIR__ . '/../settings/config.php'); // your database connection file

// Function to count total forms
function countTotalForms($conn) {
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM forms";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    } else {
        return 0; // or handle error
    }
}

// Function to count total forms by a specific user
function countFormsByUser($conn, $userId) {
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM forms WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['total'];
        }
    }
    return 0; // or handle error
}


function getAllForms(mysqli $conn) {
    $sql = "
        SELECT forms.*, users.name AS user_name
        FROM forms
        JOIN users ON forms.user_id = users.user_id
        ORDER BY forms.updated_at DESC
    ";

    $result = $conn->query($sql);

    $forms = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $forms[] = $row;
        }
    }

    return $forms;
}


function countTotalResponses($conn) {
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM form_responses";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    } else {
        return 0; // or handle error
    }
}

function countFormResponsesByForm(mysqli $conn, int $formId): int {
    $sql = "SELECT COUNT(*) as total FROM form_responses WHERE form_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        // Handle preparation error
        return 0;
    }

    mysqli_stmt_bind_param($stmt, 'i', $formId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $total;
}


function countRecentFormResponses(mysqli $conn, int $days = 7): int {
    $sql = "SELECT COUNT(*) as total 
            FROM form_responses 
            WHERE created_at >= NOW() - INTERVAL ? DAY";
    
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        // Handle preparation error
        return 0;
    }

    mysqli_stmt_bind_param($stmt, 'i', $days);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $total;
}



function timeAgo(string $datetime): string {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return $diff . ' seconds ago';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' days ago';
    } elseif ($diff < 2592000) {
        return floor($diff / 604800) . ' weeks ago';
    } elseif ($diff < 31536000) {
        return floor($diff / 2592000) . ' months ago';
    } else {
        return floor($diff / 31536000) . ' years ago';
    }
}


function countNewForms(mysqli $conn, int $days): int {
    $sql = "SELECT COUNT(*) as total FROM forms WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return 0;
    }

    mysqli_stmt_bind_param($stmt, 'i', $days);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $total;
}


function trackFormView(mysqli $conn, int $formId) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    $sql = "INSERT INTO form_views (form_id, ip_address, user_agent) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'iss', $formId, $ip, $userAgent);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}



?>
