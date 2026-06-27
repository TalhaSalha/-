<?php
require_once 'includes/db.php';

// Fetch Statistics
$total_damaged = 0;
$govt_damaged = 0;
$private_damaged = 0;

try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM buildings");
    $total_damaged = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM buildings WHERE building_type = 'government'");
    $govt_damaged = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM buildings WHERE building_type = 'private'");
    $private_damaged = $stmt->fetchColumn();

    // Fetch Settings
    $stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);

    // Default fallback
    if (!$settings) {
        $settings = [
            'site_name' => 'غزة تبني',
            'phone' => '1700-500-600',
            'email' => 'support@gaza-builds.ps',
            'facebook' => '#',
            'twitter' => '#',
            'instagram' => '#',
            'linkedin' => '#'
        ];
    }

} catch (PDOException $e) {
    // Handle error
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($settings['site_name']); ?> - نعيد البناء، نحيي الأمل</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-hammer me-2 text-gold"></i>غزة تبني
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="#hero">الرئيسية</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">الرؤية</a></li>
                    <li class="nav-item"><a class="nav-link" href="#stats">الأرقام</a></li>
                    <li class="nav-item"><a class="nav-link" href="#steps">كيف نعمل</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">تواصل معنا</a></li>
                </ul>
                <a href="admin/login.php" class="btn btn-outline-premium btn-sm ms-3">
                    <i class="fas fa-user-shield me-2"></i>الإدارة
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="hero-section">
        <div class="hero-bg-glow"></div>
        <div class="hero-bg-glow-2"></div>
        
        <div class="container hero-container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-1 order-2">
                    <div class="hero-text-content animate-fade-in-up">
                        <span class="badge badge-gold mb-4 px-3 py-2 rounded-pill fw-bold">
                            <i class="fas fa-hard-hat me-2"></i>مشروع إعادة الإعمار الوطني
                        </span>
                        <h1 class="hero-title">من تحت الركام..<br><span class="text-gradient">نصنع الحياة</span></h1>
                        <p class="hero-subtitle">
                            منصة رقمية متكاملة لتوثيق الأضرار وتوجيه جهود الإعمار بدقة وشفافية.
                            نحن هنا لنعيد بناء ما دُمر، حجراً حجراً، وأملاً أملاً.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="request.php" class="btn btn-premium btn-lg">
                                <i class="fas fa-plus-circle me-2"></i>تقديم طلب تقييم
                            </a>
                            <a href="#about" class="btn btn-outline-premium btn-lg">
                                <i class="fas fa-play me-2"></i>تعرف على المبادرة
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 order-lg-2 order-1 mb-5 mb-lg-0">
                    <div class="hero-visuals animate-fade-in-up">
                        <!-- Main Glass Card -->
                        <div class="visual-main-card">
                            <div class="visual-icon-wrapper">
                                <i class="fas fa-city fa-3x text-gold"></i>
                            </div>
                            <h3 class="text-white mb-2">إعادة الإعمار</h3>
                            <p class="text-muted small mb-4">نظام متكامل لحصر وتقييم الأضرار</p>
                            <div class="progress" style="height: 6px; background: rgba(255,255,255,0.1);">
                                <div class="progress-bar bg-gold" role="progressbar" style="width: 75%; background-color: var(--primary-color);"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted small">نسبة الإنجاز</span>
                                <span class="text-gold small fw-bold">75%</span>
                            </div>
                        </div>

                        <!-- Floating Badge 1 -->
                        <div class="visual-floating-card card-top-right">
                            <div class="bg-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <span class="d-block text-white fw-bold">تم التوثيق</span>
                                <span class="text-success small">1,250 مبنى</span>
                            </div>
                        </div>

                        <!-- Floating Badge 2 -->
                        <div class="visual-floating-card card-bottom-left">
                            <div class="bg-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-users text-dark"></i>
                            </div>
                            <div>
                                <span class="d-block text-white fw-bold">فريق العمل</span>
                                <span class="text-warning small">+50 مهندس</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="stats-section">
        <div class="container position-relative z-2">
            <div class="section-header">
                <h6 class="text-gold text-uppercase ls-2">حقائق وأرقام</h6>
                <h2 class="section-title">حجم الدمار.. وحجم التحدي</h2>
                <div class="section-line"></div>
            </div>
            
            <div class="row g-5">
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-house-damage stat-icon fa-3x mb-3 text-gold"></i>
                        <h2 class="stat-number"><?php echo number_format($total_damaged); ?></h2>
                        <p class="stat-label">إجمالي المباني المتضررة</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-university stat-icon fa-3x mb-3 text-gold"></i>
                        <h2 class="stat-number"><?php echo number_format($govt_damaged); ?></h2>
                        <p class="stat-label">منشآت عامة وحكومية</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-home stat-icon fa-3x mb-3 text-gold"></i>
                        <h2 class="stat-number"><?php echo number_format($private_damaged); ?></h2>
                        <p class="stat-label">وحدات سكنية خاصة</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section Redesigned -->
    <section id="about" class="about-modern">
        <div class="container position-relative z-2">
            <div class="row align-items-center">
                <!-- Image Side -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="about-image-container animate-fade-in-up">
                        <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=800&q=80" alt="Reconstruction Site" class="about-main-img">
                        <img src="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=500&q=80" alt="Engineers" class="about-accent-img">
                    </div>
                </div>
                
                <!-- Content Side -->
                <div class="col-lg-6 ps-lg-5">
                    <div class="about-content-wrapper animate-fade-in-up" style="animation-delay: 0.2s;">
                        <span class="about-badge">
                            <i class="fas fa-eye"></i>رؤيتنا المستقبلية
                        </span>
                        <h2 class="about-title">إعمار الإنسان<br>قبل البنيان</h2>
                        <p class="text-muted lead mb-4" style="font-size: 1.2rem; line-height: 1.8;">
                            مشروع "غزة تبني" ليس مجرد منصة رقمية، بل هو وعد بالنهوض مجدداً. نهدف لتوثيق كل حجر تضرر، ليكون أساساً لمستقبل أقوى.
                        </p>
                        <p class="text-muted mb-5">
                            من خلال الشفافية المطلقة والتقنيات الحديثة، نضمن أن كل مساعدة تصل إلى مستحقيها، وأن كل جهد يبذل يصب في مكانه الصحيح لإعادة الحياة إلى شوارعنا.
                        </p>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="feature-card-premium">
                                    <div class="feature-icon-wrapper">
                                        <i class="fas fa-check-double"></i>
                                    </div>
                                    <h5 class="feature-card-title">دقة متناهية</h5>
                                    <p class="feature-card-desc">توثيق رقمي شامل للأضرار باستخدام أحدث المعايير الهندسية لضمان الحقوق.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-card-premium">
                                    <div class="feature-icon-wrapper">
                                        <i class="fas fa-hand-holding-heart"></i>
                                    </div>
                                    <h5 class="feature-card-title">مسؤولية وأمانة</h5>
                                    <p class="feature-card-desc">نعمل بشفافية تامة مع المجتمع والجهات المانحة لضمان عدالة التوزيع.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features / Steps Section -->
    <section id="steps" class="py-5">
        <div class="container">
            <div class="section-header">
                <h6 class="text-gold text-uppercase ls-2">رحلة الإعمار</h6>
                <h2 class="section-title">كيف تبدأ عملية التقييم؟</h2>
                <div class="section-line"></div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="step-box">
                        <span class="step-number">01</span>
                        <div class="step-icon-modern">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>تقديم الطلب</h3>
                        <p>قم بتعبئة النموذج الإلكتروني ببيانات دقيقة حول العقار المتضرر وإرفاق الصور الأولية.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-box">
                        <span class="step-number">02</span>
                        <div class="step-icon-modern">
                            <i class="fas fa-search-location"></i>
                        </div>
                        <h3>المعاينة الميدانية</h3>
                        <p>ستقوم فرقنا الهندسية بزيارة الموقع للتحقق من حجم الأضرار وإعداد التقارير الفنية.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-box">
                        <span class="step-number">03</span>
                        <div class="step-icon-modern">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h3 class="text-main mb-3">الاعتماد والبدء</h3>
                        <p class="text-muted">بعد اعتماد التقييم، يتم إدراج الملف في خطط الإعمار والتواصل مع الجهات المانحة.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="bg-gradient-dark py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <h6 class="text-gold text-uppercase ls-2 mb-3">تواصل معنا</h6>
                    <h2 class="display-4 fw-bold text-main mb-4">نحن هنا لمساعدتك</h2>
                    <p class="lead text-muted mb-5">
                        لديك استفسار أو واجهت مشكلة في تقديم الطلب؟ فريق الدعم الفني جاهز للرد على استفساراتك على مدار الساعة.
                    </p>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="social-icon bg-dark border border-secondary me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-phone-alt text-gold fa-lg"></i>
                        </div>
                        <div>
                            <span class="d-block text-muted">اتصل بنا مباشرة</span>
                            <span class="h5 text-white fw-bold" dir="ltr"><?php echo htmlspecialchars($settings['phone']); ?></span>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="social-icon bg-dark border border-secondary me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-envelope text-gold fa-lg"></i>
                        </div>
                        <div>
                            <span class="d-block text-muted">راسلنا عبر البريد</span>
                            <span class="h5 text-main fw-bold"><?php echo htmlspecialchars($settings['email']); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-7">
                    <div class="form-container-glass p-5">
                        <h3 class="text-main mb-4">أرسل استفسارك</h3>
                        <form id="contactForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="الاسم الكامل" required>
                                        <label for="name">الاسم الكامل</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="البريد الإلكتروني" required>
                                        <label for="email">البريد الإلكتروني</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="message" name="message" style="height: 150px" placeholder="نص الرسالة" required></textarea>
                                        <label for="message">نص الرسالة</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-premium w-100 py-3">إرسال الرسالة</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <a class="navbar-brand d-block mb-4" href="#">
                        <i class="fas fa-hammer me-2 text-gold"></i><span class="text-main h3"><?php echo htmlspecialchars($settings['site_name']); ?></span>
                    </a>
                    <p class="text-muted">
                        منصة وطنية موحدة لإدارة وتوثيق أضرار المباني في قطاع غزة، تهدف إلى تسريع وتيرة الإعمار بشفافية وعدالة.
                    </p>
                    <div class="d-flex gap-2 mt-4">
                        <?php if(!empty($settings['facebook'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['facebook']); ?>" class="social-icon" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if(!empty($settings['twitter'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['twitter']); ?>" class="social-icon" target="_blank"><i class="fab fa-twitter"></i></a>
                        <?php endif; ?>
                        <?php if(!empty($settings['instagram'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['instagram']); ?>" class="social-icon" target="_blank"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if(!empty($settings['linkedin'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['linkedin']); ?>" class="social-icon" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-heading">روابط سريعة</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none hover-white">الرئيسية</a></li>
                        <li class="mb-2"><a href="#about" class="text-muted text-decoration-none hover-white">عن المبادرة</a></li>
                        <li class="mb-2"><a href="#stats" class="text-muted text-decoration-none hover-white">الإحصائيات</a></li>
                        <li class="mb-2"><a href="admin/login.php" class="text-muted text-decoration-none hover-white">دخول الموظفين</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-heading">المساعدة</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none hover-white">الأسئلة الشائعة</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none hover-white">شروط الاستخدام</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none hover-white">سياسة الخصوصية</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none hover-white">الدعم الفني</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5 class="footer-heading">النشرة البريدية</h5>
                    <p class="text-muted mb-4">اشترك في نشرتنا البريدية للحصول على آخر تحديثات مشاريع الإعمار.</p>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="البريد الإلكتروني">
                        <button class="btn btn-premium" type="button"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
            <hr class="border-secondary my-5">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 text-muted">&copy; 2026 جميع الحقوق محفوظة لمنصة <span class="text-gold">غزة تبني</span>.</p>
                </div>
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-muted">تم التطوير بواسطة <span class="text-white">فريق الأمل</span></p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
