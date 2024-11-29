<?php

namespace models;

require_once 'Model.php';
require_once '../config/Database.php';
require_once 'Country.php';

use config\Database;

class Location extends Country
{
    protected static $table = 'locations';

    public function __construct(
        public ?int    $location_id = null,
        public ?string $street_address = null,
        public ?string $postal_code = null,
        public ?string $city = null,
        public ?string $state_province = null,
        public ?string $country_id = null,
    )
    {
        parent::__construct($country_id);
    }



    public function getCountryName()
    {
        $countries = Country::all();
        $countryMap = [];
        foreach ($countries as $country) {
            $countryMap[$country->country_id] = $country->country_name;
        }
        return $countryMap[$this->country_id] ?? 'N/A';
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
            if (isset($this->location_id)) {
                // Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (location_id, street_address, postal_code, city, state_province, country_id) 
							VALUES (?, ?, ?, ?, ?, ?)
							ON DUPLICATE KEY UPDATE
								location_id  = VALUES (location_id),
                                street_address = VALUES (street_address),
                                postal_code = VALUES (postal_code),
                                city = VALUES (city),
                                state_province = VALUES (state_province),
                                country_id = VALUES (country_id)";
                $stmt = $db->conn->prepare($sql);
                // Vincular els valors
                $stmt->bind_param("isssss",
                    $this->location_id,
                    $this->street_address,
                    $this->postal_code,
                    $this->city,
                    $this->state_province,
                    $this->country_id
                );

                // Executar la consulta
                $stmt->execute();
            } else {
                throw new \Exception ("Location ID not provided.");
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

            if (isset($this->location_id)) {
                $sql = "SELECT * FROM $table WHERE location_id = $this->location_id";
                $result = $db->conn->query($sql);

                // Comprovar si hi ha resultats
                if ($result->num_rows == 1) {
                    $sql = "DELETE FROM $table 
								WHERE location_id = ?";
                    $stmt = $db->conn->prepare($sql);
                    // Vincular els valors
                    $stmt->bind_param("i", $this->location_id);
                    // Executar la consulta
                    $stmt->execute();
                } else {
                    throw new \Exception ("The location does not exist.");
                }
            } else {
                throw new \Exception ("Location ID not provided.");
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