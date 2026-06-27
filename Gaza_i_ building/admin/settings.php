<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$current_page = 'settings.php';
$success_msg = '';
$error_msg = '';

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = $_POST['site_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $facebook = $_POST['facebook'];
    $twitter = $_POST['twitter'];
    $instagram = $_POST['instagram'];
    $linkedin = $_POST['linkedin'];

    // Check if row exists first, if not insert
    $check = $pdo->query("SELECT COUNT(*) FROM settings");
    if ($check->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO settings (site_name, phone, email, facebook, twitter, instagram, linkedin) VALUES (?, ?, ?, ?, ?, ?, ?)");
    } else {
        $stmt = $pdo->prepare("UPDATE settings SET site_name=?, phone=?, email=?, facebook=?, twitter=?, instagram=?, linkedin=? WHERE id=1");
        // Assuming id=1 is the first row. Or better, update just the first row without WHERE id=1 if unsure, but id=1 is standard.
        // Or "UPDATE settings SET ... LIMIT 1" (MySQL supports LIMIT in UPDATE but it's safer to use ID)
        // We'll stick to updating all or id=1. Let's assume id=1.
        
        // Actually, to be safe against deleted IDs, let's get the ID first.
        $id_stmt = $pdo->query("SELECT id FROM settings LIMIT 1");
        $id = $id_stmt->fetchColumn();
        if ($id) {
            $stmt = $pdo->prepare("UPDATE settings SET site_name=?, phone=?, email=?, facebook=?, twitter=?, instagram=?, linkedin=? WHERE id=?");
             if ($stmt->execute([$site_name, $phone, $email, $facebook, $twitter, $instagram, $linkedin, $id])) {
                $success_msg = "تم حفظ الإعدادات بنجاح.";
             } else {
                $error_msg = "حدث خطأ أثناء الحفظ.";
             }
             // Avoid executing the insert logic below
             $stmt = null; 
        } else {
             // This case is covered by the count check above usually
             $stmt = $pdo->prepare("INSERT INTO settings (site_name, phone, email, facebook, twitter, instagram, linkedin) VALUES (?, ?, ?, ?, ?, ?, ?)");
             if ($stmt->execute([$site_name, $phone, $email, $facebook, $twitter, $instagram, $linkedin])) {
                $success_msg = "تم حفظ الإعدادات بنجاح.";
             } else {
                $error_msg = "حدث خطأ أثناء الحفظ.";
             }
             $stmt = null;
        }
    }
    
    // Execute Insert if stmt is not null (from first branch)
    if ($stmt) {
         if ($stmt->execute([$site_name, $phone, $email, $facebook, $twitter, $instagram, $linkedin])) {
            $success_msg = "تم حفظ الإعدادات بنجاح.";
         } else {
            $error_msg = "حدث خطأ أثناء الحفظ.";
         }
    }
}

// Fetch Settings
$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

// Default values if empty
if (!$settings) {
    $settings = [
        'site_name' => 'غزة تبني',
        'phone' => '',
        'email' => '',
        'facebook' => '',
        'twitter' => '',
        'instagram' => '',
        'linkedin' => ''
    ];
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات الموقع - غزة تبني</title>
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

             <div class="row justify-content-center animate-fade-in-up">
                <div class="col-lg-10">
                    <form method="POST">
                        <!-- General Settings Section -->
                        <div class="glass-card p-4 mb-4">
                            <div class="d-flex align-items-center mb-4 border-bottom border-secondary pb-3">
                                <div class="bg-gold rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background-color: rgba(212, 175, 55, 0.2); color: var(--primary-color);">
                                    <i class="fas fa-globe fa-lg"></i>
                                </div>
                                <h4 class="text-white mb-0 fw-bold">معلومات الموقع الأساسية</h4>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label text-white-50">اسم الموقع</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-gold"><i class="fas fa-heading"></i></span>
                                        <input type="text" class="form-control bg-dark text-white border-secondary" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" placeholder="أدخل اسم الموقع">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white-50">رقم الهاتف</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-gold"><i class="fas fa-phone-alt"></i></span>
                                        <input type="text" class="form-control bg-dark text-white border-secondary" name="phone" value="<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>" placeholder="مثال: 0599123456">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white-50">البريد الإلكتروني للدعم</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-gold"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control bg-dark text-white border-secondary" name="email" value="<?php echo htmlspecialchars($settings['email'] ?? ''); ?>" placeholder="admin@example.com">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Section -->
                        <div class="glass-card p-4 mb-4">
                            <div class="d-flex align-items-center mb-4 border-bottom border-secondary pb-3">
                                <div class="bg-gold rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background-color: rgba(212, 175, 55, 0.2); color: var(--primary-color);">
                                    <i class="fas fa-share-alt fa-lg"></i>
                                </div>
                                <h4 class="text-white mb-0 fw-bold">روابط التواصل الاجتماعي</h4>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label text-white-50">فيسبوك</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary" style="color: #1877F2;"><i class="fab fa-facebook-f"></i></span>
                                        <input type="url" class="form-control bg-dark text-white border-secondary" name="facebook" value="<?php echo htmlspecialchars($settings['facebook'] ?? ''); ?>" placeholder="https://facebook.com/...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white-50">تويتر (X)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary" style="color: #1DA1F2;"><i class="fab fa-twitter"></i></span>
                                        <input type="url" class="form-control bg-dark text-white border-secondary" name="twitter" value="<?php echo htmlspecialchars($settings['twitter'] ?? ''); ?>" placeholder="https://twitter.com/...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white-50">انستغرام</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary" style="color: #E1306C;"><i class="fab fa-instagram"></i></span>
                                        <input type="url" class="form-control bg-dark text-white border-secondary" name="instagram" value="<?php echo htmlspecialchars($settings['instagram'] ?? ''); ?>" placeholder="https://instagram.com/...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white-50">لينكد إن</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary" style="color: #0077B5;"><i class="fab fa-linkedin-in"></i></span>
                                        <input type="url" class="form-control bg-dark text-white border-secondary" name="linkedin" value="<?php echo htmlspecialchars($settings['linkedin'] ?? ''); ?>" placeholder="https://linkedin.com/in/...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-premium px-5 py-3 d-flex align-items-center gap-2">
                                <i class="fas fa-save fa-lg"></i>
                                <span class="fs-5">حفظ التغييرات</span>
                            </button>
                        </div>
                    </form>
                </div>
             </div>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
