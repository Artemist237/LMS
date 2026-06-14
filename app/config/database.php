<?php
// app/config/database.php

class Database {
    private static $host = "localhost";
    private static $db_name = "lms_db";
    private static $username = "root";
    private static $password = "alan237"; // Mets ton mot de passe MySQL ici s'il y en a un
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8mb4",
                    self::$username,
                    self::$password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $exception) {
                die("Erreur de connexion à la base de données : " . $exception->getMessage());
            }
        }
        return self::$conn;
    }
}