<?php
session_start();
require_once '../includes/db.php';

// Check login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$current_page = 'users.php';

// Handle Add Admin
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($username) || empty($password)) {
            $error_msg = "يرجى ملء جميع الحقول.";
        } elseif ($password !== $confirm_password) {
            $error_msg = "كلمتا المرور غير متطابقتين.";
        } else {
            // Check if username exists
            $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error_msg = "اسم المستخدم موجود مسبقاً.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                // Check if created_at column exists or just insert username/password
                // Safest is to try insert with created_at, if fails, fallback? 
                // Better: just insert username/password. created_at usually has default CURRENT_TIMESTAMP
                $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
                if ($stmt->execute([$username, $hashed_password])) {
                    $success_msg = "تم إضافة المسؤول بنجاح.";
                } else {
                    $error_msg = "حدث خطأ أثناء الإضافة.";
                }
            }
        }
    } elseif ($_POST['action'] === 'delete') {
        $id = $_POST['id'];
        // Prevent deleting self
        if ($id == $_SESSION['admin_id']) {
            $error_msg = "لا يمكنك حذف حسابك الحالي.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
            if ($stmt->execute([$id])) {
                $success_msg = "تم حذف المسؤول بنجاح.";
            } else {
                $error_msg = "حدث خطأ أثناء الحذف.";
            }
        }
    }
}

// Fetch Admins
$stmt = $pdo->query("SELECT * FROM admins ORDER BY id DESC");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين - غزة تبني</title>
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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-white fw-bold"><i class="fas fa-users-cog me-2 text-gold"></i>إدارة المسؤولين</h3>
                <button type="button" class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                    <i class="fas fa-plus me-2"></i>إضافة مسؤول جديد
                </button>
            </div>

            <div class="admin-table-container shadow-lg animate-fade-in-up">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">اسم المستخدم</th>
                                <th scope="col">تاريخ الإضافة</th>
                                <th scope="col">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $index => $admin): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-gold rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; background-color: var(--primary-color);">
                                            <i class="fas fa-user text-dark"></i>
                                        </div>
                                        <?php echo htmlspecialchars($admin['username']); ?>
                                    </div>
                                </td>
                                <td><?php echo isset($admin['created_at']) ? $admin['created_at'] : '-'; ?></td>
                                <td>
                                    <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                        <button onclick="deleteUser(<?php echo $admin['id']; ?>)" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> حذف</button>
                                    <?php else: ?>
                                        <span class="badge bg-success">حسابك الحالي</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Admin Modal -->
        <div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content glass-card border-0" style="background-color: var(--secondary-color);">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title text-white">إضافة مسؤول جديد</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="action" value="add">
                            <div class="mb-3">
                                <label class="form-label text-white-50">اسم المستخدم</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-gold"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control bg-dark text-white border-secondary" name="username" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white-50">كلمة المرور</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-gold"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control bg-dark text-white border-secondary" name="password" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white-50">تأكيد كلمة المرور</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-gold"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control bg-dark text-white border-secondary" name="confirm_password" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-premium">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <?php include 'includes/footer.php'; ?>
        
        <script>
        function deleteUser(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من استرجاع هذا الحساب!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذفه!',
                cancelButtonText: 'إلغاء',
                background: '#1e293b',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('delete_user.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'id=' + id
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Toast.fire({
                                icon: 'success',
                                title: 'تم حذف المسؤول بنجاح'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'خطأ!',
                                text: data.message || 'حدث خطأ أثناء الحذف',
                                icon: 'error',
                                background: '#1e293b',
                                color: '#fff'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'خطأ!',
                            text: 'حدث خطأ في الاتصال',
                            icon: 'error',
                            background: '#1e293b',
                            color: '#fff'
                        });
                    });
                }
            })
        }
        </script>
    </div>
</body>
</html>
