<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=Ecoride_db;charset=utf8mb4", "root", "");
    echo "PDO fonctionne !";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

