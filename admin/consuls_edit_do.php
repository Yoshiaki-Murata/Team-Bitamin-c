<?php
require_once __DIR__ . '/../inc/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];

    $db = db_connect();

    $sql = "UPDATE consultants SET name = :name WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: consuls.php');
    exit;
}
