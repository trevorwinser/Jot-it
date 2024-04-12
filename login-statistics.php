<?php
function getLoginStats($conn, $selectedDate) {
    $stats = ['today' => 0, 'week' => 0, 'month' => 0];

    // Today's logins
    $todayQuery = "SELECT COUNT(DISTINCT user_id) AS logins_today FROM user_activity WHERE DATE(login_time) = ?";
    $todayStmt = $conn->prepare($todayQuery);
    $todayStmt->bind_param("s", $selectedDate);
    $todayStmt->execute();
    $todayResult = $todayStmt->get_result();

    if ($todayResult) {
        if ($todayRow = $todayResult->fetch_assoc()) {
            $stats['today'] = $todayRow['logins_today'];
        }
    }

    // This week's logins
    $weekQuery = "SELECT COUNT(DISTINCT user_id) AS logins_week FROM user_activity WHERE YEARWEEK(login_time, 1) = YEARWEEK(?, 1)";
    $weekStmt = $conn->prepare($weekQuery);
    $weekStmt->bind_param("s", $selectedDate);
    $weekStmt->execute();
    $weekResult = $weekStmt->get_result();

    if ($weekResult) {
        if ($weekRow = $weekResult->fetch_assoc()) {
            $stats['week'] = $weekRow['logins_week'];
        }
    }

    // This month's logins
    $monthQuery = "SELECT COUNT(DISTINCT user_id) AS logins_month FROM user_activity WHERE MONTH(login_time) = MONTH(?) AND YEAR(login_time) = YEAR(?)";
    $monthStmt = $conn->prepare($monthQuery);
    $monthStmt->bind_param("ss", $selectedDate, $selectedDate);
    $monthStmt->execute();
    $monthResult = $monthStmt->get_result();

    if ($monthResult) {
        if ($monthRow = $monthResult->fetch_assoc()) {
            $stats['month'] = $monthRow['logins_month'];
        }
    }

    return $stats;
}
?>
