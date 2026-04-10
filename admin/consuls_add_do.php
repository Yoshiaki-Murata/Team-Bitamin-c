<?php
require_once __DIR__ . '/../inc/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    $db = db_connect();

    $sql = "INSERT INTO consultants (name) VALUES (:name)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: consuls.php');
    exit;
}
