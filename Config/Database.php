<?php
namespace Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $conn = null;
    private static ?string $lastError = null;

    /**
     * Retourne la connexion PDO
     *
     * @return PDO|null
     */
    public static function getConnection(): ?PDO
    {
        if (self::$conn === null) {
            try {
                // Paramètres de connexion
                $host = 'localhost';
                $db   = 'ecoride_db';
                $user = 'root';
                $pass = '';
                $charset = 'utf8mb4';

                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

                self::$conn = new PDO($dsn, $user, $pass);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Pas d'erreur
                self::$lastError = null;

            } catch (PDOException $e) {
                self::$lastError = $e->getMessage();
                error_log('[Database Error] ' . $e->getMessage());
                self::$conn = null; // Retourne null si échec
            }
        }

        return self::$conn;
    }

    /**
     * Retourne le dernier message d'erreur PDO
     *
     * @return string|null
     */
    public static function getLastError(): ?string
    {
        return self::$lastError;
    }

    /**
     * Vérifie si la connexion est établie
     *
     * @return bool
     */
    public static function isConnected(): bool
    {
        return self::$conn instanceof PDO;
    }
}
