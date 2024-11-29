<?php

namespace models;

require_once 'Model.php';
require_once '../config/Database.php';

use config\Database;

class Region extends Model
{
    protected static $table = 'regions';

    public function __construct(
        public ?int    $region_id = null,
        public ?string $region_name = null,
    )
    {
    }

    public function save()
    {
        error_reporting(E_ALL);
        try {
            $db = new Database();
            $db->connectDB('C:/temp/config.db');

            $table = static::$table;

            if (isset($this->region_id)) {
                $sql = "INSERT INTO $table (region_id, region_name)
                        VALUES (?, ?)
                        ON DUPLICATE KEY UPDATE
                        region_id = VALUES(region_id),
                        region_name = VALUES(region_name)";
                $stmt = $db->conn->prepare($sql);
                $stmt->bind_param("is", $this->region_id, $this->region_name);
                $stmt->execute();
            } else {
                throw new \Exception("Region ID not provided.");
            }

            $db->conn->commit();
        } catch (\mysqli_sql_exception $e) {
            if ($db->conn)
                $db->conn->rollback();
            throw new \mysqli_sql_exception($e->getMessage());
        } finally {
            if ($db->conn)
                $db->closeDB();
        }
    }

    public function destroy()
    {
        error_reporting(E_ALL);
        try {
            $db = new Database();
            $db->connectDB('C:/temp/config.db');

            $table = static::$table;

            if (isset($this->region_id)) {
                $sql = "SELECT * FROM $table WHERE region_id = $this->region_id";
                $result = $db->conn->query($sql);

                if ($result->num_rows == 1) {
                    $sql = "DELETE FROM $table WHERE region_id = ?";
                    $stmt = $db->conn->prepare($sql);
                    $stmt->bind_param("i", $this->region_id);
                    $stmt->execute();
                } else {
                    throw new \Exception("The region does not exist.");
                }
            } else {
                throw new \Exception("Region ID not provided.");
            }
            $db->conn->commit();
        } catch (\mysqli_sql_exception $e) {
            if ($db->conn)
                $db->conn->rollback();
            throw new \mysqli_sql_exception($e->getMessage());
        } finally {
            if ($db->conn)
                $db->closeDB();
        }
    }
}