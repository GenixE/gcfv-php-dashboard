<?php

use config\Database;

require_once 'config/Database.php';
require_once 'models/Account.php';

session_start();
ob_start();

try {
    $db = new Database();
    if (!$db->connectDB('C:/temp/config.db')) {
        throw new Exception("Database connection failed.");
    }

    $username = htmlspecialchars($_POST['username'] ?? '');
    $password = htmlspecialchars($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);

    if ($username && $password) {
        $stmt = $db->conn->prepare("SELECT password, is_admin FROM accounts WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password, $is_admin);
            $stmt->fetch();
            $stmt->close();

            if (password_verify($password, $hashed_password)) {
                session_regenerate_id(true);
                $_SESSION['username'] = $username;
                $_SESSION['is_admin'] = (bool)$is_admin; // Store is_admin as a boolean

                if ($remember) {
                    setcookie('username', $username, time() + (86400 * 30), "/");
                } else {
                    setcookie('username', '', time() - 3600, "/");
                }

                $lastLoginStmt = $db->conn->prepare("UPDATE accounts SET last_login = CURRENT_TIMESTAMP WHERE username = ?");
                $lastLoginStmt->bind_param("s", $username);
                $lastLoginStmt->execute();
                $lastLoginStmt->close();

                $db->conn->commit();
                header("Location: html/dashboard.php");
                exit();
            }
        }
        $_SESSION['error'] = "Incorrect username or password.";
    } else {
        $_SESSION['error'] = "Please provide both username and password.";
    }

    $db->closeDB();
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    error_log("Error: " . $e->getMessage());
}

ob_end_flush();
header("Location: ../index.php");
exit();