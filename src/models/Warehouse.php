<?php

namespace models;

require_once 'Model.php';
require_once '../config/Database.php';

use config\Database;

class Warehouse extends Model
{
    protected static $table = 'warehouses';

    public function __construct(
        public ?int    $warehouse_id = null,
        public ?string $warehouse_name = null,
        public ?int    $location_id = null,
        public ?string $warehouse_spec = null,
        public ?string $wh_geo_location = null,
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
            if (isset($this->warehouse_id)) {
                // Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (warehouse_id, warehouse_name, location_id, warehouse_spec, wh_geo_location) 
							VALUES (?, ?, ?, ?, ?)
							ON DUPLICATE KEY UPDATE
								warehouse_id  = VALUES (warehouse_id),
                                warehouse_name = VALUES (warehouse_name),
                                location_id  = VALUES (location_id),
                                warehouse_spec = VALUES (warehouse_spec),
                                wh_geo_location = VALUES (wh_geo_location)";
                $stmt = $db->conn->prepare($sql);
                // Vincular els valors
                $stmt->bind_param("isiss",
                    $this->warehouse_id,
                    $this->warehouse_name,
                    $this->location_id,
                    $this->warehouse_spec,
                    $this->wh_geo_location
                );

                // Executar la consulta
                $stmt->execute();
            } else {
                throw new \Exception ("Warehouse ID not provided.");
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

            if (isset($this->warehouse_id)) {
                $sql = "SELECT * FROM $table WHERE warehouse_id = $this->warehouse_id";
                $result = $db->conn->query($sql);

                // Comprovar si hi ha resultats
                if ($result->num_rows == 1) {
                    $sql = "DELETE FROM $table 
								WHERE warehouse_id = ?";
                    $stmt = $db->conn->prepare($sql);
                    // Vincular els valors
                    $stmt->bind_param("i", $this->warehouse_id);
                    // Executar la consulta
                    $stmt->execute();
                } else {
                    throw new \Exception ("The warehouse does not exist.");
                }
            } else {
                throw new \Exception ("Warehouse ID not provided.");
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