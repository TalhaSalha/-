<?php
session_start();
require_once '../includes/db.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        header("Location: index.php");
        exit;
    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المسؤول - غزة تبني</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            overflow: hidden;
            background-color: var(--dark-bg);
        }
        .login-container {
            height: 100vh;
        }
        .login-sidebar {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.7)), 
                        url('https://images.unsplash.com/photo-1541888946425-d81bb19240f5?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .login-sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at center, transparent 0%, #0f172a 100%);
        }
        .login-content {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 10;
        }
        .glass-form-wrapper {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
        }
        .input-group-text {
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-color);
            border-left: none;
            color: var(--primary-color);
        }
        .form-control {
            border-right: none;
            background-color: rgba(15, 23, 42, 0.6);
            color: white;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
            background-color: rgba(15, 23, 42, 0.8);
            color: white;
        }
        .form-control:focus + .input-group-text {
            border-color: var(--primary-color);
        }
    </style>
</head>
<body>
    
    <div class="container-fluid p-0">
        <div class="row g-0 login-container">
            <!-- Right Side: Form -->
            <div class="col-lg-5 login-content bg-dark position-relative">
                <!-- Ambient Glow -->
                <div class="hero-bg-glow" style="top: -10%; right: -10%; width: 300px; height: 300px;"></div>
                <div class="hero-bg-glow-2" style="bottom: -10%; left: -10%; width: 300px; height: 300px;"></div>

                <div class="glass-form-wrapper animate-fade-in-up">
                    <div class="text-center mb-5">
                        <i class="fas fa-hammer fa-3x text-gold mb-3"></i>
                        <h2 class="fw-bold text-main mb-2">مرحباً بعودتك</h2>
                        <p class="text-muted">قم بتسجيل الدخول للمتابعة إلى لوحة التحكم</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger d-flex align-items-center mb-4 rounded-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label text-white small fw-bold">اسم المستخدم</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control ps-3" placeholder="أدخل اسم المستخدم" required>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label text-white small fw-bold">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control ps-3" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-premium w-100 py-3 fw-bold shadow-lg">
                            تسجيل الدخول <i class="fas fa-arrow-left ms-2"></i>
                        </button>
                    </form>

                    <div class="text-center mt-5">
                        <a href="../index.php" class="text-muted text-decoration-none small hover-gold transition-300">
                            <i class="fas fa-home me-1"></i> العودة للصفحة الرئيسية
                        </a>
                    </div>
                </div>
            </div>

            <!-- Left Side: Image/Branding -->
            <div class="col-lg-7 login-sidebar d-none d-lg-flex">
                <div class="text-center position-relative z-2 p-5">
                    <div class="mb-4">
                        <span class="badge badge-gold px-3 py-2 rounded-pill mb-3" style="background: rgba(212, 175, 55, 0.2); color: var(--primary-color); border: 1px solid var(--primary-color);">إدارة النظام</span>
                    </div>
                    <h1 class="display-4 fw-bold mb-3 text-white">غزة تبني</h1>
                    <p class="lead text-white-50 mx-auto" style="max-width: 500px;">
                        منصة وطنية موحدة لتوثيق الأضرار وإعادة الإعمار. نعمل معاً لبناء مستقبل أفضل وأكثر إشراقاً.
                    </p>
                    <div class="mt-5 d-flex justify-content-center gap-3">
                        <div class="glass-card px-4 py-3 rounded-4 border-0" style="background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(10px);">
                            <h3 class="text-gold fw-bold mb-0">24/7</h3>
                            <small class="text-white-50">دعم فني</small>
                        </div>
                        <div class="glass-card px-4 py-3 rounded-4 border-0" style="background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(10px);">
                            <h3 class="text-gold fw-bold mb-0">100%</h3>
                            <small class="text-white-50">أمان وحماية</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
