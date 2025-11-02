<?php
    require 'Database.php';
    $stmt = $db->checkExist("SELECT * FROM `users_tbl` WHERE `Email` != 'hamxah4u@gmail.com' ");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
?>