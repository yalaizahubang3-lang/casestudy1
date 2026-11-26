<?php
require 'config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
}

if (!$id || $id < 1) {
    http_response_code(400);
    exit('Invalid plant ID. <a href="index.php">Back</a>');
}

$stmt = $pdo->prepare("SELECT id, plant_name, growing_area FROM plants WHERE id = ?");
$stmt->execute([$id]);
$plant = $stmt->fetch();

if (!$plant) {
    http_response_code(404);
    exit('Plant record not found. <a href="index.php">Back</a>');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['confirm'] ?? '') === 'yes') {
    $delete = $pdo->prepare("DELETE FROM plants WHERE id = ?");
    $delete->execute([$id]);
    header('Location: index.php');
    exit;
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Plant</title>
</head>
<body>
    <h1>Confirm Delete</h1>
    <p>Are you sure you want to delete this plant entry?</p>
    <p><strong>Plant Name:</strong> <?= e($plant['plant_name']) ?></p>
    <p><strong>Growing Area:</strong> <?= e($plant['growing_area']) ?></p>

    <form method="post" action="delete.php">
        <input type="hidden" name="id" value="<?= e($id) ?>">
        <input type="hidden" name="confirm" value="yes">
        <button type="submit">Yes, Delete</button>
    </form>
    <p><a href="index.php">Cancel</a></p>
</body>
</html>
