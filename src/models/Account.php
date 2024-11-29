<?php

namespace models;

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/../config/Database.php';

use config\Database;

class Account extends Model
{
    protected static $table = 'accounts';

    public function __construct(
        public ?int    $acc_id = null,
        public ?string $username = null,
        public ?string $password = null,
        public ?int    $is_admin = null,
        public ?string $last_login = null,
        public ?string $last_logout = null,
        public ?string $account_created = null
    )
    {
    }

    public static function initialize()
    {
        $db = new Database();
        $db->connectDB('C:/temp/config.db');

        $sql = "SHOW TABLES LIKE 'accounts'";
        $result = $db->conn->query($sql);

        if ($result->num_rows == 0) {
            $createTableSQL = file_get_contents(__DIR__ . '/../sql/accounts.sql');
            if ($createTableSQL) {
                $db->conn->query($createTableSQL);
                $admin = new self(
                    acc_id: 1,
                    username: 'admin',
                    password: password_hash('admin', PASSWORD_DEFAULT),
                    is_admin: true,
                    account_created: date('Y-m-d H:i:s')
                );
                $admin->save();
            } else {
                throw new \Exception("accounts.sql file is empty or does not exist.");
            }
        } else {
            $sql = "SELECT * FROM accounts WHERE is_admin = 1";
            $result = $db->conn->query($sql);

            if ($result->num_rows == 0) {
                $admin = new self(
                    acc_id: 1,
                    username: 'admin',
                    password: password_hash('admin', PASSWORD_DEFAULT),
                    is_admin: true,
                    account_created: date('Y-m-d H:i:s')
                );
                $admin->save();
            }
        }

        $db->closeDB();
    }

    public function save()
    {
        $db = new Database();
        $db->connectDB('C:/temp/config.db');

        $table = static::$table;
        $sql = "INSERT INTO $table (acc_id, username, password, is_admin, last_login, last_logout, account_created)
                VALUES (?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    username = VALUES(username),
                    password = VALUES(password),
                    is_admin = VALUES(is_admin)";

        // Only update last_login, last_logout, and account_created if they are not null
        if ($this->last_login !== null) {
            $sql .= ", last_login = VALUES(last_login)";
        }
        if ($this->last_logout !== null) {
            $sql .= ", last_logout = VALUES(last_logout)";
        }
        if ($this->account_created !== null) {
            $sql .= ", account_created = VALUES(account_created)";
        }

        $stmt = $db->conn->prepare($sql);
        $stmt->bind_param("issssss",
            $this->acc_id,
            $this->username,
            $this->password,
            $this->is_admin,
            $this->last_login,
            $this->last_logout,
            $this->account_created
        );
        $stmt->execute();
        $db->conn->commit();
        $db->closeDB();
    }

    public function destroy()
    {
        $db = new Database();
        $db->connectDB('C:/temp/config.db');

        $table = static::$table;
        if (isset($this->acc_id)) {
            $sql = "DELETE FROM $table WHERE acc_id = ?";
            $stmt = $db->conn->prepare($sql);
            $stmt->bind_param("i", $this->acc_id);
            $stmt->execute();
            $db->conn->commit();
        } else {
            throw new \Exception("Account ID not provided.");
        }
        $db->closeDB();
    }

    public static function findByUsername($username)
    {
        $db = new Database();
        $db->connectDB('C:/temp/config.db');

        $sql = "SELECT * FROM accounts WHERE username = ?";
        $stmt = $db->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user_data = $result->fetch_assoc();
            $account = new self(
                acc_id: $user_data['acc_id'],
                username: $user_data['username'],
                password: $user_data['password'],
                is_admin: $user_data['is_admin'],
                last_login: $user_data['last_login'],
                last_logout: $user_data['last_logout'],
                account_created: $user_data['account_created']
            );
            return $account;
        } else {
            return null;
        }

        $db->closeDB();
    }
}