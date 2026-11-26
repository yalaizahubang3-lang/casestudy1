<?php
require 'config.php';
$stmt = $pdo->query("SELECT id, plant_name, variety, growing_area, quantity FROM plants ORDER BY id");
$plants = $stmt->fetchAll();

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Plant Nursery Information System</title>
</head>
<body>
    <h1>Plant Nursery Information System</h1>
    <p><a href="create.php">Add New Plant</a></p>

    <?php if (count($plants) > 0): ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>ID</th>
                <th>Plant Name</th>
                <th>Variety</th>
                <th>Growing Area</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($plants as $plant): ?>
                <tr>
                    <td><?= e($plant['id']) ?></td>
                    <td><?= e($plant['plant_name']) ?></td>
                    <td><?= e($plant['variety']) ?></td>
                    <td><?= e($plant['growing_area']) ?></td>
                    <td><?= e($plant['quantity']) ?></td>
                    <td>
                        <a href="view.php?id=<?= e($plant['id']) ?>">View</a> |
                        <a href="edit.php?id=<?= e($plant['id']) ?>">Edit</a> |
                        <a href="delete.php?id=<?= e($plant['id']) ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No plant records found.</p>
    <?php endif; ?>
</body>
</html>
