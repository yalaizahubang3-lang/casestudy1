<?php
require 'config.php';

$plant_name = '';
$variety = '';
$growing_area = '';
$quantity = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plant_name = trim($_POST['plant_name'] ?? '');
    $variety = trim($_POST['variety'] ?? '');
    $growing_area = trim($_POST['growing_area'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    if ($plant_name === '' || strlen($plant_name) > 150) {
        $errors[] = 'Plant name is required and must not exceed 150 characters.';
    }
    if ($variety === '' || strlen($variety) > 100) {
        $errors[] = 'Variety is required and must not exceed 100 characters.';
    }
    if ($growing_area === '' || strlen($growing_area) > 50) {
        $errors[] = 'Growing area is required and must not exceed 50 characters.';
    }
    if ($quantity === '' || !preg_match('/^\d+$/', $quantity)) {
        $errors[] = 'Quantity is required and must be a whole number greater than or equal to zero.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            "INSERT INTO plants (plant_name, variety, growing_area, quantity) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$plant_name, $variety, $growing_area, $quantity]);
        header('Location: index.php');
        exit;
    }
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Plant</title>
</head>
<body>
    <h1>Add New Plant</h1>

    <?php if ($errors): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="create.php">
        <p>
            <label>Plant Name:
                <input type="text" name="plant_name" maxlength="150" required value="<?= e($plant_name) ?>">
            </label>
        </p>
        <p>
            <label>Variety:
                <input type="text" name="variety" maxlength="100" required value="<?= e($variety) ?>">
            </label>
        </p>
        <p>
            <label>Growing Area:
                <input type="text" name="growing_area" maxlength="50" required value="<?= e($growing_area) ?>">
            </label>
        </p>
        <p>
            <label>Quantity:
                <input type="number" name="quantity" min="0" step="1" required value="<?= e($quantity) ?>">
            </label>
        </p>
        <button type="submit">Save Plant</button>
    </form>
    <p><a href="index.php">Cancel / Back</a></p>
</body>
</html>
