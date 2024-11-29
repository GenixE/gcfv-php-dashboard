<?php

namespace models;

require_once 'Model.php';
require_once '../config/Database.php';

use config\Database;

class Product extends Model
{
    protected static $table = 'product_information';

    public function __construct(
        public ?int    $product_id = null,
        public ?string $product_name = null,
        public ?string $product_description = null,
        public ?int    $category_id = null,
        public ?float  $weight_class = null,
        public ?string $warranty_period = null,
        public ?float  $supplier_id = null,
        public ?string $product_status = null,
        public ?float  $list_price = null,
        public ?float  $min_price = null,
        public ?string $catalog_url = null,
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
            if (isset($this->product_id)) {
                // Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (product_id, product_name, product_description, category_id, weight_class, warranty_period, supplier_id, product_status, list_price, min_price, catalog_url) 
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
							ON DUPLICATE KEY UPDATE
                                product_id  = VALUES (product_id),
                                product_name = VALUES (product_name),
                                product_description  = VALUES (product_description),
                                category_id = VALUES (category_id),
                                weight_class = VALUES (weight_class),
                                warranty_period = VALUES (warranty_period),
                                supplier_id = VALUES (supplier_id),
                                product_status = VALUES (product_status),
                                list_price = VALUES (list_price),
                                min_price = VALUES (min_price),
                                catalog_url = VALUES (catalog_url)";
                $stmt = $db->conn->prepare($sql);
                // Vincular els valors
                $stmt->bind_param("issidssidds",
                    $this->product_id,
                    $this->product_name,
                    $this->product_description,
                    $this->category_id,
                    $this->weight_class,
                    $this->warranty_period,
                    $this->supplier_id,
                    $this->product_status,
                    $this->list_price,
                    $this->min_price,
                    $this->catalog_url
                );

                // Executar la consulta
                $stmt->execute();
            } else {
                throw new \Exception ("Product ID not provided.");
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

            if (isset($this->product_id)) {
                $sql = "SELECT * FROM $table WHERE product_id = $this->product_id";
                $result = $db->conn->query($sql);

                // Comprovar si hi ha resultats
                if ($result->num_rows == 1) {
                    $sql = "DELETE FROM $table 
								WHERE product_id = ?";
                    $stmt = $db->conn->prepare($sql);
                    // Vincular els valors
                    $stmt->bind_param("i", $this->product_id);
                    // Executar la consulta
                    $stmt->execute();
                } else {
                    throw new \Exception ("The product does not exist.");
                }
            } else {
                throw new \Exception ("Product ID not provided.");
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