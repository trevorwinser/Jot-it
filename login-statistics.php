<?php
function getLoginStats($conn) {
    $stats = ['today' => 0, 'week' => 0, 'month' => 0];

    // Today's logins
    $todayQuery = "SELECT COUNT(DISTINCT user_id) AS logins_today FROM user_activity WHERE DATE(login_time) = CURDATE()";
    if ($todayResult = $conn->query($todayQuery)) {
        if ($todayRow = $todayResult->fetch_assoc()) {
            $stats['today'] = $todayRow['logins_today'];
        }
    }

    // This week's logins
    $weekQuery = "SELECT COUNT(DISTINCT user_id) AS logins_week FROM user_activity WHERE YEARWEEK(login_time, 1) = YEARWEEK(CURDATE(), 1)";
    if ($weekResult = $conn->query($weekQuery)) {
        if ($weekRow = $weekResult->fetch_assoc()) {
            $stats['week'] = $weekRow['logins_week'];
        }
    }

    // This month's logins
    $monthQuery = "SELECT COUNT(DISTINCT user_id) AS logins_month FROM user_activity WHERE MONTH(login_time) = MONTH(CURDATE()) AND YEAR(login_time) = YEAR(CURDATE())";
    if ($monthResult = $conn->query($monthQuery)) {
        if ($monthRow = $monthResult->fetch_assoc()) {
            $stats['month'] = $monthRow['logins_month'];
        }
    }

    return $stats;
}
?>
