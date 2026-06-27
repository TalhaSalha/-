<?php
require_once 'includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $building_id = $_POST['building_id'] ?? '';
    $owner_id = $_POST['owner_id'] ?? '';

    if (empty($owner_id)) {
        echo json_encode(['success' => false, 'message' => 'رقم الهوية مطلوب']);
        exit;
    }

    try {

        $sql = "SELECT * FROM buildings WHERE owner_id = :owner_id";
        $params = [':owner_id' => $owner_id];

        if (!empty($building_id)) {
            $sql .= " AND id = :id";
            $params[':id'] = $building_id;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $building = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($building) {
            echo json_encode(['success' => true, 'found' => true, 'data' => $building]);
        } else {
            echo json_encode(['success' => true, 'found' => false]);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Request']);
}
?>