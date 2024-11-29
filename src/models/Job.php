<?php

namespace models;

require_once 'Model.php';
require_once '../config/Database.php';

use config\Database;

class Job extends Model
{
    protected static $table = 'jobs';

    public function __construct(
        public ?string $job_id = null,
        public ?string $job_title = null,
        public ?float  $min_salary = null,
        public ?float  $max_salary = null,
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
            if (isset($this->job_id)) {
                // Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (job_id, job_title, min_salary, max_salary) 
							VALUES (?, ?, ?, ?)
							ON DUPLICATE KEY UPDATE
								job_id  = VALUES (job_id),
                                job_title = VALUES (job_title),
                                min_salary = VALUES (min_salary),
                                max_salary = VALUES (max_salary)";
                $stmt = $db->conn->prepare($sql);
                // Vincular els valors
                $stmt->bind_param("ssdd",
                    $this->job_id,
                    $this->job_title,
                    $this->min_salary,
                    $this->max_salary
                );

                // Executar la consulta
                $stmt->execute();
            } else {
                throw new \Exception ("Job ID not provided.");
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

        if (isset($this->job_id)) {
            $sql = "SELECT * FROM $table WHERE job_id = ?";
            $stmt = $db->conn->prepare($sql);
            $stmt->bind_param("s", $this->job_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Comprovar si hi ha resultats
            if ($result->num_rows == 1) {
                $sql = "DELETE FROM $table WHERE job_id = ?";
                $stmt = $db->conn->prepare($sql);
                // Vincular els valors
                $stmt->bind_param("s", $this->job_id);
                // Executar la consulta
                $stmt->execute();
            } else {
                throw new \Exception("The job does not exist.");
            }
        } else {
            throw new \Exception("Job ID not provided.");
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