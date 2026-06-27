<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$current_page = 'profile.php';
$success_msg = '';
$error_msg = '';
$admin_id = $_SESSION['admin_id'];

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username)) {
        $error_msg = "اسم المستخدم مطلوب.";
    } else {
        // Check if username taken by another admin
        $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ? AND id != ?");
        $stmt->execute([$username, $admin_id]);
        if ($stmt->fetch()) {
            $error_msg = "اسم المستخدم مستخدم بالفعل.";
        } else {
            if (!empty($password)) {
                if ($password !== $confirm_password) {
                    $error_msg = "كلمتا المرور غير متطابقتين.";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE admins SET username = ?, password = ? WHERE id = ?");
                    if ($stmt->execute([$username, $hashed_password, $admin_id])) {
                        $success_msg = "تم تحديث الملف الشخصي وكلمة المرور بنجاح.";
                    } else {
                        $error_msg = "حدث خطأ أثناء التحديث.";
                    }
                }
            } else {
                $stmt = $pdo->prepare("UPDATE admins SET username = ? WHERE id = ?");
                if ($stmt->execute([$username, $admin_id])) {
                    $success_msg = "تم تحديث الملف الشخصي بنجاح.";
                } else {
                    $error_msg = "حدث خطأ أثناء التحديث.";
                }
            }
        }
    }
}

// Fetch Admin Info
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي - غزة تبني</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="content">
        <?php include 'includes/header.php'; ?>
        
        <div class="container-fluid pt-4 px-4">
             <?php if ($success_msg): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo $success_msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if ($error_msg): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

             <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="glass-card p-5">
                        <div class="text-center mb-5">
                            <div class="bg-gold rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; background-color: var(--primary-color);">
                                <i class="fas fa-user-shield fa-3x text-dark"></i>
                            </div>
                            <h3 class="text-white"><?php echo htmlspecialchars($admin['username']); ?></h3>
                            <p class="text-muted">مدير النظام</p>
                        </div>

                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label text-white-50">اسم المستخدم</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-gold"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control bg-dark text-white border-secondary" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                                </div>
                            </div>
                            
                            <hr class="border-secondary my-4">
                            <h5 class="text-white mb-3">تغيير كلمة المرور</h5>
                            <p class="text-muted small mb-3">اترك الحقول فارغة إذا كنت لا تريد تغيير كلمة المرور</p>

                            <div class="mb-3">
                                <label class="form-label text-white-50">كلمة المرور الجديدة</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-gold"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control bg-dark text-white border-secondary" name="password">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-white-50">تأكيد كلمة المرور</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-gold"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control bg-dark text-white border-secondary" name="confirm_password">
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-premium py-2 d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas fa-save"></i>
                                    <span>حفظ التغييرات</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
             </div>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
