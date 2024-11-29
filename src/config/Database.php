<?php

namespace config;

class Database
{
    public $conn;

    // Load database configuration from a file
    public static function loadConfig($filePath): array
    {
        $config = [];

        if (file_exists($filePath)) {
            $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                $line = trim($line);
                if (strpos($line, '#') !== 0) {
                    list($key, $value) = explode('=', $line, 2);
                    $config[trim($key)] = trim($value);
                }
            }
        } else {
            throw new \Exception("Configuration file not found.");
        }

        return $config;
    }

    // Connect to the database
    public function connectDB($configPath): bool
    {
        try {
            $config = self::loadConfig($configPath);

            // Ensure required config keys are present
            $requiredKeys = ['DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE', 'DB_PORT'];
            foreach ($requiredKeys as $key) {
                if (!isset($config[$key])) {
                    throw new \Exception("Missing configuration for $key");
                }
            }

            // Attempt to connect to the database
            $this->conn = new \mysqli($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD'], $config['DB_DATABASE'], $config['DB_PORT']);

            if ($this->conn->connect_error) {
                throw new \Exception("Connection error: " . $this->conn->connect_error);
            }

            $this->conn->autocommit(false);
            $this->conn->begin_transaction();
            return true;

        } catch (\Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            return false;
        }
    }

    // Close the database connection
    public function closeDB()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

?>
