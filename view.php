<?php
require 'config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    exit('Invalid plant ID. <a href="index.php">Back</a>');
}

$stmt = $pdo->prepare("SELECT * FROM plants WHERE id = ?");
$stmt->execute([$id]);
$plant = $stmt->fetch();

if (!$plant) {
    http_response_code(404);
    exit('Plant record not found. <a href="index.php">Back</a>');
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Plant</title>
</head>
<body>
    <h1>Plant Details</h1>
    <p><strong>ID:</strong> <?= e($plant['id']) ?></p>
    <p><strong>Plant Name:</strong> <?= e($plant['plant_name']) ?></p>
    <p><strong>Variety:</strong> <?= e($plant['variety']) ?></p>
    <p><strong>Growing Area:</strong> <?= e($plant['growing_area']) ?></p>
    <p><strong>Quantity:</strong> <?= e($plant['quantity']) ?></p>
    <p><a href="index.php">Back</a></p>
</body>
</html>
