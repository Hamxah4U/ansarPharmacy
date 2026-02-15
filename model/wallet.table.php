<?php
    require 'Database.php';
    $stmt = $db->checkExist("SELECT w.amount, w.datewithdraw, w.timewithdrawa, w.reason, u.Fullname FROM wallet w JOIN users_tbl u ON w.user_id = u.userID ORDER BY w.datewithdraw DESC, w.timewithdrawa DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
?>