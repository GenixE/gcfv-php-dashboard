<?php

namespace models;

require_once 'Model.php';
require_once '../config/Database.php';
require_once 'Region.php';

use config\Database;

class Country extends Region
{
    protected static $table = 'countries';

    public function __construct(
        public ?string $country_id = null,
        public ?string $country_name = null,
        public ?int $region_id = null,
    )
    {
        parent::__construct($region_id);
    }

    public function getRegionName()
    {
        $countries = Country::all();
        $regions = Region::all();
        $countryMap = [];
        $regionMap = [];
        foreach ($countries as $country) {
            $countryMap[$country->country_id] = $country->region_id;
        }
        foreach ($regions as $region) {
            $regionMap[$region->region_id] = $region->region_name;
        }
        $regionId = $countryMap[$this->country_id] ?? null;
        return $regionMap[$regionId] ?? 'N/A';
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
            if (isset($this->country_id)) {
                // Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (country_id, country_name, region_id) 
                            VALUES (?, ?, ?)
                            ON DUPLICATE KEY UPDATE
                                country_id = VALUES (country_id),
                                country_name = VALUES (country_name),
                                region_id = VALUES (region_id)";
                $stmt = $db->conn->prepare($sql);
                // Vincular els valors
                $stmt->bind_param("sss",
                    $this->country_id,
                    $this->country_name,
                    $this->region_id
                );

                // Executar la consulta
                $stmt->execute();
            } else {
                throw new \Exception("Country ID not provided.");
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

            if (isset($this->country_id)) {
                $sql = "SELECT * FROM $table WHERE country_id = ?";
                $stmt = $db->conn->prepare($sql);
                $stmt->bind_param("s", $this->country_id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Comprovar si hi ha resultats
                if ($result->num_rows == 1) {
                    $sql = "DELETE FROM $table WHERE country_id = ?";
                    $stmt = $db->conn->prepare($sql);
                    // Vincular els valors
                    $stmt->bind_param("s", $this->country_id);
                    // Executar la consulta
                    $stmt->execute();
                } else {
                    throw new \Exception("The country does not exist.");
                }
            } else {
                throw new \Exception("Country ID not provided.");
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