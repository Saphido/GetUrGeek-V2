<?php
session_start();

/**
 * Infos connexion BD
 */
$host    = '127.0.0.1';
$db      = 'geturgeek';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';
$dsn     = "mysql:host=$host;dbname=$db;charset=$charset";

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