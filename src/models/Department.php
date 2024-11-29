<?php

namespace models;

require_once 'Model.php';
require_once '../config/Database.php';

use config\Database;

class Department extends Model
{
    protected static $table = 'departments';

    public function __construct(
        public ?int    $department_id = null,
        public ?string $department_name = null,
        public ?int    $manager_id = null,
        public ?int    $location_id = null,
    )
    {
    }

    public function save()
    {
        error_reporting(E_ALL);
        try {
            // Connectar a la base de dades
            $db = new Database();
            $db->connectDB('C:/temp/config.db');
            //$db->conn->autocommit(false);
            //$db->conn->begin_transaction();

            // Obtenir el nom de la taula de la classe filla
            $table = static::$table;

            // Connectar a la base de dades
            if (isset($this->department_id)) {
                // Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (department_id, department_name, manager_id, location_id) 
							VALUES (?, ?, ?, ?)
							ON DUPLICATE KEY UPDATE
								department_id  = VALUES (department_id),
                                department_name = VALUES (department_name),
                                manager_id = VALUES (manager_id),
                                location_id = VALUES (location_id)";
                $stmt = $db->conn->prepare($sql);
                // Vincular els valors
                $stmt->bind_param("isis",
                    $this->department_id,
                    $this->department_name,
                    $this->manager_id,
                    $this->location_id
                );

                // Executar la consulta
                $stmt->execute();
            } else {
                throw new \Exception ("Department ID not provided.");
            }

            $db->conn->commit();
        } catch (\mysqli_sql_exception $e) {
            if ($db->conn)
                $db->conn->rollback();
            throw new \mysqli_sql_exception($e->getMessage());
        } finally {
            if ($db->conn)
                // Tancar la connexió
                $db->closeDB();
        }
    }

    public function destroy()
    {
        error_reporting(E_ALL);
        try {
            // Connectar a la base de dades
            $db = new Database();
            $db->connectDB('C:/temp/config.db');
            //$db->conn->autocommit(false);
            //$db->conn->begin_transaction();

            // Obtenir el nom de la taula de la classe filla
            $table = static::$table;

            if (isset($this->department_id)) {
                $sql = "SELECT * FROM $table WHERE department_id = $this->department_id";
                $result = $db->conn->query($sql);

                // Comprovar si hi ha resultats
                if ($result->num_rows == 1) {
                    $sql = "DELETE FROM $table 
								WHERE department_id = ?";
                    $stmt = $db->conn->prepare($sql);
                    // Vincular els valors
                    $stmt->bind_param("i", $this->department_id);
                    // Executar la consulta
                    $stmt->execute();
                } else {
                    throw new \Exception ("The department does not exist.");
                }
            } else {
                throw new \Exception ("Department ID not provided.");
            }
            $db->conn->commit();
        } catch (\mysqli_sql_exception $e) {
            if ($db->conn)
                $db->conn->rollback();
            throw new \mysqli_sql_exception($e->getMessage());
        } finally {
            if ($db->conn)
                // Tancar la connexió
                $db->closeDB();
        }
    }
}




