<?php

function getLoginStats($conn) {
    // Today's logins
    $todayQuery = "SELECT COUNT(DISTINCT user_id) AS logins_today FROM user_activity WHERE DATE(login_time) = CURDATE()";
    $todayResult = $conn->query($todayQuery);
    $todayRow = $todayResult->fetch_assoc();
    $loginsToday = $todayRow['logins_today'];

    // This week's logins
    $weekQuery = "SELECT COUNT(DISTINCT user_id) AS logins_week FROM user_activity WHERE YEARWEEK(login_time, 1) = YEARWEEK(CURDATE(), 1)";
    $weekResult = $conn->query($weekQuery);
    $weekRow = $weekResult->fetch_assoc();
    $loginsWeek = $weekRow['logins_week'];

    // This month's logins
    $monthQuery = "SELECT COUNT(DISTINCT user_id) AS logins_month FROM user_activity WHERE MONTH(login_time) = MONTH(CURDATE()) AND YEAR(login_time) = YEAR(CURDATE())";
    $monthResult = $conn->query($monthQuery);
    $monthRow = $monthResult->fetch_assoc();
    $loginsMonth = $monthRow['logins_month'];

    return [
        'today' => $loginsToday,
        'week' => $loginsWeek,
        'month' => $loginsMonth
    ];
}


$stats = getLoginStats($conn);
$loginsToday = $stats['today'];
$loginsWeek = $stats['week'];
$loginsMonth = $stats['month'];
?>
