<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/db.php';

// Handle Filter
$where = "1";
$params = [];

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $where .= " AND status = :status";
    $params[':status'] = $_GET['status'];
}

if (isset($_GET['type']) && !empty($_GET['type'])) {
    $where .= " AND building_type = :type";
    $params[':type'] = $_GET['type'];
}

// Fetch Buildings
$sql = "SELECT * FROM buildings WHERE $where ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Stats
$stats = [
    'total' => $pdo->query("SELECT COUNT(*) FROM buildings")->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM buildings WHERE status = 'pending'")->fetchColumn(),
    'approved' => $pdo->query("SELECT COUNT(*) FROM buildings WHERE status = 'approved'")->fetchColumn(),
    'rejected' => $pdo->query("SELECT COUNT(*) FROM buildings WHERE status = 'rejected'")->fetchColumn(),
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - غزة تبني</title>
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
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in-up">
                <div>
                    <h2 class="fw-bold text-white mb-1">لوحة التحكم</h2>
                    <p class="text-muted mb-0">نظرة عامة على نشاط الموقع</p>
                </div>
                <button class="btn btn-premium shadow-sm" onclick="window.print()">
                    <i class="fas fa-print me-2"></i> تقرير
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="col-md-6 col-lg-3">
                    <div class="admin-stat-card h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-bold">إجمالي الطلبات</p>
                                <h3 class="text-white fw-bold mb-0"><?php echo $stats['total']; ?></h3>
                            </div>
                            <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-folder-open fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="admin-stat-card h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-bold">قيد المراجعة</p>
                                <h3 class="text-warning fw-bold mb-0"><?php echo $stats['pending']; ?></h3>
                            </div>
                            <div class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-clock fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="admin-stat-card h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-bold">تمت الموافقة</p>
                                <h3 class="text-success fw-bold mb-0"><?php echo $stats['approved']; ?></h3>
                            </div>
                            <div class="p-3 rounded-circle bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="admin-stat-card h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small fw-bold">مرفوضة</p>
                                <h3 class="text-danger fw-bold mb-0"><?php echo $stats['rejected']; ?></h3>
                            </div>
                            <div class="p-3 rounded-circle bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-times-circle fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search -->
            <div class="admin-stat-card mb-4 animate-fade-in-up" style="animation-delay: 0.2s;">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">حالة الطلب</label>
                        <select name="status" class="form-select text-white" style="background-color: rgba(15, 23, 42, 0.8); border-color: rgba(255,255,255,0.1);">
                            <option value="">كل الحالات</option>
                            <option value="pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : ''; ?>>قيد المراجعة</option>
                            <option value="approved" <?php echo (isset($_GET['status']) && $_GET['status'] == 'approved') ? 'selected' : ''; ?>>موافق عليه</option>
                            <option value="rejected" <?php echo (isset($_GET['status']) && $_GET['status'] == 'rejected') ? 'selected' : ''; ?>>مرفوض</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">نوع المبنى</label>
                        <select name="type" class="form-select text-white" style="background-color: rgba(15, 23, 42, 0.8); border-color: rgba(255,255,255,0.1);">
                            <option value="">كل الأنواع</option>
                            <option value="government" <?php echo (isset($_GET['type']) && $_GET['type'] == 'government') ? 'selected' : ''; ?>>حكومي</option>
                            <option value="private" <?php echo (isset($_GET['type']) && $_GET['type'] == 'private') ? 'selected' : ''; ?>>خاص</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-premium flex-grow-1"><i class="fas fa-filter me-2"></i>تصفية النتائج</button>
                            <a href="index.php" class="btn btn-outline-light"><i class="fas fa-sync-alt"></i></a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div class="admin-table-container shadow-lg animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th class="py-3 px-4">#</th>
                                <th class="py-3 px-4">صورة المبنى</th>
                                <th class="py-3 px-4">تفاصيل الطلب</th>
                                <th class="py-3 px-4">النوع</th>
                                <th class="py-3 px-4">العنوان</th>
                                <th class="py-3 px-4">الحالة</th>
                                <th class="py-3 px-4 text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($buildings)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">لا توجد طلبات مطابقة للبحث</td>
                            </tr>
                            <?php endif; ?>

                            <?php foreach ($buildings as $b): ?>
                            <tr>
                                <td class="px-4 text-muted fw-bold"><?php echo $b['id']; ?></td>
                                <td class="px-4">
                                    <?php if ($b['image_path']): ?>
                                        <a href="../<?php echo $b['image_path']; ?>" target="_blank" class="d-block position-relative" style="width: 60px; height: 60px;">
                                            <img src="../<?php echo $b['image_path']; ?>" class="rounded-3 w-100 h-100 object-fit-cover border border-secondary" alt="Building">
                                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center opacity-0 hover-opacity-100 transition-300">
                                                <i class="fas fa-search-plus text-white"></i>
                                            </div>
                                        </a>
                                    <?php else: ?>
                                        <div class="bg-secondary bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 60px;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4">
                                    <h6 class="text-white mb-1 fw-bold"><?php echo htmlspecialchars($b['building_name']); ?></h6>
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="fas fa-user me-1"></i> <?php echo htmlspecialchars($b['submitter_name']); ?>
                                    </div>
                                    <div class="d-flex align-items-center text-muted small mt-1">
                                        <i class="fas fa-calendar-alt me-1"></i> <?php echo date('Y/m/d', strtotime($b['created_at'])); ?>
                                    </div>
                                </td>
                                <td class="px-4">
                                    <?php if ($b['building_type'] == 'government'): ?>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3">حكومي</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-light border border-secondary border-opacity-25 rounded-pill px-3">خاص</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 text-muted small" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo htmlspecialchars($b['address']); ?>
                                </td>
                                <td class="px-4">
                                    <?php 
                                        if ($b['status'] == 'pending') echo '<span class="status-badge status-pending"><i class="fas fa-clock me-1"></i> قيد المراجعة</span>';
                                        elseif ($b['status'] == 'approved') echo '<span class="status-badge status-approved"><i class="fas fa-check me-1"></i> موافق عليه</span>';
                                        else echo '<span class="status-badge status-rejected"><i class="fas fa-times me-1"></i> مرفوض</span>';
                                    ?>
                                </td>
                                <td class="px-4 text-center">
                                    <div class="btn-group shadow-sm rounded-pill" role="group">
                                        <button type="button" class="btn btn-sm btn-success px-3" onclick="updateStatus(<?php echo $b['id']; ?>, 'approved')" title="موافقة">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger px-3" onclick="updateStatus(<?php echo $b['id']; ?>, 'rejected')" title="رفض">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination (Static for now) -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top border-secondary border-opacity-25">
                    <small class="text-muted">عرض <?php echo count($buildings); ?> من أصل <?php echo $stats['total']; ?> طلبات</small>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><a class="page-link bg-transparent border-secondary text-muted" href="#">السابق</a></li>
                            <li class="page-item active"><a class="page-link bg-primary border-primary text-white" href="#">1</a></li>
                            <li class="page-item"><a class="page-link bg-transparent border-secondary text-muted" href="#">2</a></li>
                            <li class="page-item"><a class="page-link bg-transparent border-secondary text-muted" href="#">التالي</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        
        <script>
            function updateStatus(id, status) {
                const actionText = status === 'approved' ? 'الموافقة على' : 'رفض';
                const confirmButtonText = status === 'approved' ? 'نعم، وافق!' : 'نعم، ارفض!';
                const confirmButtonColor = status === 'approved' ? '#198754' : '#d33';

                Swal.fire({
                    title: `هل أنت متأكد من ${actionText} هذا الطلب؟`,
                    text: "سيتم تحديث حالة الطلب.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: confirmButtonColor,
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: 'إلغاء',
                    background: '#1e293b',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('update_status.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `id=${id}&status=${status}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: `تم ${actionText} الطلب بنجاح`
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'خطأ!',
                                    text: data.message || 'حدث خطأ أثناء تحديث الحالة',
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
        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
