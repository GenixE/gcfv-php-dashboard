<?php

namespace models;

require_once 'Model.php';
require_once '../config/Database.php';

use config\Database;

class Category extends Model
{
    protected static $table = 'categories_tab';

    public function __construct(
        public ?int    $category_id = null,
        public ?string $category_name = null,
        public ?string $category_description = null,
        public ?int    $parent_category_id = null,
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
            if (isset($this->category_id)) {
                // Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (category_id, category_name, category_description, parent_category_id) 
							VALUES (?, ?, ?, ?)
							ON DUPLICATE KEY UPDATE
								category_id  = VALUES (category_id),
                                category_name = VALUES (category_name),
                                category_description  = VALUES (category_description),
                                parent_category_id = VALUES (parent_category_id)";
                $stmt = $db->conn->prepare($sql);
                // Vincular els valors
                $stmt->bind_param("issi",
                    $this->category_id,
                    $this->category_name,
                    $this->category_description,
                    $this->parent_category_id
                );

                // Executar la consulta
                $stmt->execute();
            } else {
                throw new \Exception ("Category ID not provided.");
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

            if (isset($this->category_id)) {
                $sql = "SELECT * FROM $table WHERE category_id = $this->category_id";
                $result = $db->conn->query($sql);

                // Comprovar si hi ha resultats
                if ($result->num_rows == 1) {
                    $sql = "DELETE FROM $table 
								WHERE category_id = ?";
                    $stmt = $db->conn->prepare($sql);
                    // Vincular els valors
                    $stmt->bind_param("i", $this->category_id);
                    // Executar la consulta
                    $stmt->execute();
                } else {
                    throw new \Exception ("The category does not exist.");
                }
            } else {
                throw new \Exception ("Category ID not provided.");
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