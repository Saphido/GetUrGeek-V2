<?php
session_start();
/**
 * Infos connexion BD
 */
$host    = 'db-mysql-ams3-66868-do-user-11684170-0.b.db.ondigitalocean.com';
$db      = 'geturgeek';
$user    = 'geturgeekadmin';
$port    = '25060';
$pass    = 'AVNS_sD3ENZ7XTZqj6OY';
$charset = 'utf8mb4';
$dsn     = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

/**
 * Attributs de la BD
 */
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

/**
 * Connexion à la db
 */
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die($e->getMessage());
}

?>