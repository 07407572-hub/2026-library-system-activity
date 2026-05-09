<?php

namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;
use PDOStatement;
use App\Config\EnvParser;

/**
 * Database configuration class implementing singleton pattern
 * Manages database connection and provides PDO instance
 *
 * @author Charles Kenneth Velasco
 * @since 1.0.0
 */
class DatabaseConfig{

    private static ?DatabaseConfig $instance = null;
    private ?PDO $pdo = null;
    
    private $config;

    // Private constructor to prevent direct instantiation
    private function __construct() {
            $env = new EnvParser();
            $env->load(__DIR__ . '/../../.env');
            $this->loadConfig();
            $this->connect();
    }

    // Load database configuration from environment variables
    private function loadConfig()
    {
        $this->config = [
            'host' => getenv('DB_HOST') ?: 'localhost',
            'port' => getenv('DB_PORT') ?: '3306',
            'name' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
            'driver' => getenv('DB_DRIVER') ?: 'mysql'
        ];

        // Validate required configuration
        if (!$this->config['name'] || !$this->config['user']) {
            throw new \Exception("Database name and user are required in .env file");
        }
    }

    // Establish database connection using PDO
    private function connect()
    {
        try {
            $dsn = sprintf(
                "%s:host=%s;port=%s;dbname=%s;charset=%s",
                $this->config['driver'],
                $this->config['host'],
                $this->config['port'],
                $this->config['name'],
                $this->config['charset']
            );

            $this->pdo = new PDO(
                $dsn,
                $this->config['user'],
                $this->config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserialization of the instance
    public function __wakeup() {
        throw new RuntimeException("Cannot unserialize singleton");
    }

    /**
     * Get the singleton instance of DatabaseConfig
     *
     * @return DatabaseConfig The singleton instance
     */
    public static function getInstance(): DatabaseConfig {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the PDO connection instance
     *
     * @return PDO The PDO connection
     */
    public function getConnection(){
        return $this->pdo;
    }

    /**
     * Prepare a SQL statement for execution
     *
     * @param string $sql The SQL statement to prepare
     * @return PDOStatement The prepared statement
     */
    public function prepare(string $sql): PDOStatement {
        return $this->pdo->prepare($sql);
    }

    /**
     * Execute a SQL query
     *
     * @param string $sql The SQL query to execute
     * @return PDOStatement The result statement
     */
    public function query(string $sql): PDOStatement {
        return $this->pdo->query($sql);
    }

    /**
     * Get the last inserted ID
     *
     * @return string The last inserted ID
     */
    public function lastInsertId(): string {
        return $this->pdo->lastInsertId();
    }

    /**
     * Begin a database transaction
     *
     * @return bool True on success
     */
    public function beginTransaction(): bool {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit the current transaction
     *
     * @return bool True on success
     */
    public function commit(): bool {
        return $this->pdo->commit();
    }

    /**
     * Roll back the current transaction
     *
     * @return bool True on success
     */
    public function rollBack(): bool {
        return $this->pdo->rollBack();
    }
}

?>