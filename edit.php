<?php
require 'config.php';

$errors = [];
$id = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
} else {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
}

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

$plant_name = $plant['plant_name'];
$variety = $plant['variety'];
$growing_area = $plant['growing_area'];
$quantity = (string)$plant['quantity'];

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
        $update = $pdo->prepare(
            "UPDATE plants SET plant_name = ?, variety = ?, growing_area = ?, quantity = ? WHERE id = ?"
        );
        $update->execute([$plant_name, $variety, $growing_area, $quantity, $id]);
        header('Location: view.php?id=' . $id);
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
    <title>Edit Plant</title>
</head>
<body>
    <h1>Edit Plant (ID: <?= e($id) ?>)</h1>

    <?php if ($errors): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="edit.php">
        <input type="hidden" name="id" value="<?= e($id) ?>">
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
        <button type="submit">Update Plant</button>
    </form>
    <p><a href="view.php?id=<?= e($id) ?>">Cancel</a></p>
</body>
</html>
