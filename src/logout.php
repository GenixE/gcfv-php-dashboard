<?php

use config\Database;

session_start();

if (isset($_SESSION['username'])) {
    require_once 'config/Database.php';

    $db = new Database();
    if ($db->connectDB('C:/temp/config.db')) {
        $stmt = $db->conn->prepare("UPDATE accounts SET last_logout = CURRENT_TIMESTAMP WHERE username = ?");
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $stmt->close();
        $db->conn->commit();
        $db->closeDB();
    }
}

session_destroy();
header("Location: ../index.php");
exit();