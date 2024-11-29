<?php

namespace models;

require_once 'Model.php';
require_once '../config/Database.php';

use config\Database;

class Order extends Model
{
    protected static $table = 'orders';

    public function __construct(
        public ?int    $order_id = null,
        public ?string $order_date = null,
        public ?string $order_mode = null,
        public ?int    $customer_id = null,
        public ?int    $order_status = null,
        public ?float  $order_total = null,
        public ?int    $sales_rep_id = null,
        public ?int    $promotion_id = null,
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
            if (isset($this->customer_id)) {
                // Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (order_id, order_date, order_mode, customer_id, order_status, order_total, sales_rep_id, promotion_id) 
							VALUES (?, ?, ?, ?, ?, ?, ?, ?)
							ON DUPLICATE KEY UPDATE
							    order_id  = VALUES (order_id),
                                order_date = VALUES (order_date),
                                order_mode  = VALUES (order_mode),
                                customer_id = VALUES (customer_id),
                                order_status = VALUES (order_status),
                                order_total = VALUES (order_total),
                                sales_rep_id = VALUES (sales_rep_id),
                                promotion_id = VALUES (promotion_id)";
                $stmt = $db->conn->prepare($sql);
                // Vincular els valors
                $stmt->bind_param("isiiidii",
                    $this->order_id,
                    $this->order_date,
                    $this->order_mode,
                    $this->customer_id,
                    $this->order_status,
                    $this->order_total,
                    $this->sales_rep_id,
                    $this->promotion_id
                );

                // Executar la consulta
                $stmt->execute();
            } else {
                throw new \Exception ("Order ID not provided.");
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

            if (isset($this->order_id)) {
                $sql = "SELECT * FROM $table WHERE order_id = $this->order_id";
                $result = $db->conn->query($sql);

                // Comprovar si hi ha resultats
                if ($result->num_rows == 1) {
                    $sql = "DELETE FROM $table 
								WHERE order_id = ?";
                    $stmt = $db->conn->prepare($sql);
                    // Vincular els valors
                    $stmt->bind_param("i", $this->order_id);
                    // Executar la consulta
                    $stmt->execute();
                } else {
                    throw new \Exception ("The order does not exist.");
                }
            } else {
                throw new \Exception ("Order ID not provided.");
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