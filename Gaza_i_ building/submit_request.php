<?php
header('Content-Type: application/json');
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Validate inputs (Basic validation)
    if (empty($_POST['submitter_name']) || empty($_POST['submitter_phone']) || empty($_POST['building_name']) || empty($_POST['owner_id'])) {
        throw new Exception("الرجاء تعبئة جميع الحقول المطلوبة بما في ذلك رقم الهوية.");
    }

    $requestType = $_POST['request_type'] ?? 'create';
    $existingId = $_POST['existing_id'] ?? '';
    $ownerId = $_POST['owner_id'];

    // Handle File Upload
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imagePath = null;
    if (isset($_FILES['building_image']) && $_FILES['building_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['building_image']['tmp_name'];
        $fileName = $_FILES['building_image']['name'];
        $fileSize = $_FILES['building_image']['size'];
        $fileType = $_FILES['building_image']['type'];
        
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadDir . $newFileName;
            
            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $imagePath = $dest_path;
            } else {
                throw new Exception("فشل في رفع الصورة.");
            }
        } else {
            throw new Exception("نوع الملف غير مدعوم. المسموح: " . implode(',', $allowedfileExtensions));
        }
    }

    if ($requestType === 'update' && !empty($existingId)) {
        // Update existing record
        $sql = "UPDATE buildings SET 
                submitter_name = :name, 
                submitter_phone = :phone, 
                submitter_email = :email, 
                building_name = :b_name, 
                building_type = :b_type, 
                address = :address, 
                damage_type = :d_type, 
                description = :desc, 
                additional_info = :info,
                status = 'pending'"; // Reset status to pending on update
        
        $params = [
            ':name' => $_POST['submitter_name'],
            ':phone' => $_POST['submitter_phone'],
            ':email' => $_POST['submitter_email'],
            ':b_name' => $_POST['building_name'],
            ':b_type' => $_POST['building_type'],
            ':address' => $_POST['address'],
            ':d_type' => $_POST['damage_type'],
            ':desc' => $_POST['description'],
            ':info' => $_POST['additional_info'],
            ':id' => $existingId,
            ':owner_id' => $ownerId
        ];

        if ($imagePath) {
            $sql .= ", image_path = :img";
            $params[':img'] = $imagePath;
        }

        $sql .= " WHERE id = :id AND owner_id = :owner_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $action = "updated";
        $building_id = $existingId;

    } else {
        // Insert new record
        $sql = "INSERT INTO buildings (owner_id, submitter_name, submitter_phone, submitter_email, building_name, building_type, address, damage_type, description, image_path, additional_info, status) 
                VALUES (:owner_id, :name, :phone, :email, :b_name, :b_type, :address, :d_type, :desc, :img, :info, 'pending')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':owner_id' => $ownerId,
            ':name' => $_POST['submitter_name'],
            ':phone' => $_POST['submitter_phone'],
            ':email' => $_POST['submitter_email'],
            ':b_name' => $_POST['building_name'],
            ':b_type' => $_POST['building_type'],
            ':address' => $_POST['address'],
            ':d_type' => $_POST['damage_type'],
            ':desc' => $_POST['description'],
            ':img' => $imagePath ?? '', // Allow empty image for new records if not provided
            ':info' => $_POST['additional_info']
        ]);
        
        $building_id = $pdo->lastInsertId();
        $action = "created";
    }

    echo json_encode(['success' => true, 'message' => 'تم حفظ الطلب بنجاح', 'action' => $action, 'building_id' => $building_id]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>