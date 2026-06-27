<?php
require_once 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقديم طلب تقييم مبنى - غزة تبني</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="request-page-bg">
    <div class="hero-bg-glow"></div>
    <div class="hero-bg-glow-2"></div>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-hammer me-2 text-gold"></i>غزة تبني
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">الرئيسية</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#about">الرؤية</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#stats">الأرقام</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#steps">كيف نعمل</a></li>
                    <li class="nav-item"><a class="nav-link active" href="request.php">تقديم طلب</a></li>
                </ul>
                <a href="admin/login.php" class="btn btn-outline-premium btn-sm ms-3">
                    <i class="fas fa-user-shield me-2"></i>الإدارة
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold text-main mb-3">تقديم طلب تقييم مبنى</h1>
                    <p class="text-muted lead">ساعدنا في حصر الأضرار من خلال تقديم بيانات دقيقة وموثقة.</p>
                </div>

                <div class="form-container-glass animate-fade-in-up">
                    
                    <!-- Stepper Header -->
                    <div class="step-indicator-modern">
                        <div class="progress-line"></div>
                        <div class="step-modern active" data-step="1">
                            <div class="step-circle">1</div>
                            <div class="step-title">التحقق</div>
                        </div>
                        <div class="step-modern" data-step="2">
                            <div class="step-circle">2</div>
                            <div class="step-title">الشخص</div>
                        </div>
                        <div class="step-modern" data-step="3">
                            <div class="step-circle">3</div>
                            <div class="step-title">المبنى</div>
                        </div>
                        <div class="step-modern" data-step="4">
                            <div class="step-circle">4</div>
                            <div class="step-title">معلومات</div>
                        </div>
                        <div class="step-modern" data-step="5">
                            <div class="step-circle">5</div>
                            <div class="step-title">تأكيد</div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form id="assessmentForm" enctype="multipart/form-data">
                        <input type="hidden" name="request_type" id="requestType" value="create">
                        <input type="hidden" name="existing_id" id="existingId" value="">

                        <!-- Step 1: Verification -->
                        <div class="step-content active" id="step1">
                            <h4 class="mb-4 text-gold border-bottom pb-2"><i class="fas fa-id-card me-2"></i>التحقق من البيانات</h4>
                            <div class="row g-4 justify-content-center">
                                <div class="col-md-8">
                                    <div class="alert alert-info-custom border-0 text-main">
                                        <i class="fas fa-info-circle me-2 text-gold"></i>
                                        أدخل رقم الهوية ورقم المبنى (إن وجد) للتحقق مما إذا كان لديك طلب سابق للتعديل عليه، أو للمتابعة كطلب جديد.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">رقم هوية المالك <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                        <input type="number" class="form-control" name="owner_id" id="ownerIdInput" placeholder="رقم الهوية (9 أرقام)" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">رقم المبنى (اختياري)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        <input type="number" class="form-control" name="building_id_check" id="buildingIdInput" placeholder="رقم الطلب السابق">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Personal Info -->
                        <div class="step-content" id="step2">
                            <h4 class="mb-4 text-gold border-bottom pb-2"><i class="fas fa-user-shield me-2"></i>بيانات مالك المبنى</h4>
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-white">الاسم الكامل <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" name="submitter_name" placeholder="الاسم الرباعي كما في الهوية" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-white">رقم الهاتف <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" name="submitter_phone" placeholder="059xxxxxxx" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-white">البريد الإلكتروني <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" name="submitter_email" placeholder="email@example.com" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Building Info -->
                        <div class="step-content" id="step3">
                            <h4 class="mb-4 text-gold border-bottom pb-2"><i class="fas fa-house-damage me-2"></i>بيانات المبنى والضرر</h4>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-white">اسم المبنى / العائلة <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        <input type="text" class="form-control" name="building_name" placeholder="مثال: عمارة النور" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-white">نوع المبنى <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                                        <select class="form-select" name="building_type" required>
                                            <option value="" selected disabled>اختر النوع...</option>
                                            <option value="private">خاص (منزل/عمارة سكنية)</option>
                                            <option value="government">حكومي (مدرسة/مستشفى/مؤسسة)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-white">العنوان بالتفصيل <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" name="address" placeholder="المحافظة - المدينة - الشارع - أقرب معلم" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-white">نوع الضرر <span class="text-danger">*</span></label>
                                    <div class="card bg-transparent border p-3">
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="damage_type" id="damagePartial" value="partial" required>
                                                <label class="form-check-label text-main" for="damagePartial">جزئي</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="damage_type" id="damageTotal" value="total">
                                                <label class="form-check-label text-main" for="damageTotal">كلي</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-white">وصف الضرر</label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="وصف مختصر لطبيعة الأضرار..."></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-white">صورة المبنى <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="building_image" accept="image/*" required>
                                    <div class="form-text text-muted"><i class="fas fa-info-circle me-1"></i> يرجى رفع صورة واضحة تظهر حجم الضرر.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Additional Info -->
                        <div class="step-content" id="step4">
                            <h4 class="mb-4 text-gold border-bottom pb-2"><i class="fas fa-clipboard-list me-2"></i>معلومات إضافية</h4>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-white">ملاحظات إضافية</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                    <textarea class="form-control" name="additional_info" rows="6" placeholder="هل هناك سكان حالياً؟ هل المبنى آيل للسقوط؟ أي تفاصيل أخرى تود إضافتها..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Review -->
                        <div class="step-content" id="step5">
                            <h4 class="mb-4 text-gold border-bottom pb-2"><i class="fas fa-check-circle me-2"></i>مراجعة البيانات</h4>
                            <div class="alert alert-gold border-0">
                                <i class="fas fa-exclamation-triangle me-2"></i> يرجى التأكد من صحة البيانات قبل الإرسال النهائي. لا يمكن تعديل الطلب بعد الإرسال.
                            </div>
                            <div class="card bg-transparent border">
                                <div class="card-body">
                                    <ul class="list-group list-group-flush" id="reviewList">
                                        <!-- Populated by JS -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-5 pt-3 border-top border-secondary">
                        <button type="button" class="btn btn-outline-premium px-4" id="prevBtn" disabled>
                            <i class="fas fa-arrow-right me-2"></i>السابق
                        </button>
                        <button type="button" class="btn btn-premium px-4" id="nextBtn">
                            التالي<i class="fas fa-arrow-left ms-2"></i>
                        </button>
                        <button type="button" class="btn btn-success-custom btn-lg px-4 d-none" id="submitBtn">
                            <i class="fas fa-paper-plane me-2"></i>إرسال الطلب
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--secondary-color); color: var(--text-main); border: 1px solid var(--primary-color);">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title text-gold">تأكيد التعديل</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد من رغبتك في تعديل بيانات هذا الطلب؟
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-premium" id="confirmEditBtn">نعم، تعديل</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 start-0 p-3" style="z-index: 1100;">
      <div id="liveToast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: var(--secondary-color); color: var(--text-main); border: 1px solid var(--primary-color);">
        <div class="d-flex">
          <div class="toast-body">
            <i class="fas fa-check-circle text-gold me-2"></i>
            <span id="toastMessage">تمت العملية بنجاح</span>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <a class="navbar-brand d-block mb-4" href="#">
                        <i class="fas fa-hammer me-2 text-gold"></i><span class="text-main h3">غزة تبني</span>
                    </a>
                    <p class="text-muted">
                        منصة وطنية موحدة لإدارة وتوثيق أضرار المباني في قطاع غزة، تهدف إلى تسريع وتيرة الإعمار بشفافية وعدالة.
                    </p>
                    <div class="d-flex gap-2 mt-4">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-heading">روابط سريعة</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-muted text-decoration-none hover-white">الرئيسية</a></li>
                        <li class="mb-2"><a href="index.php#about" class="text-muted text-decoration-none hover-white">عن المبادرة</a></li>
                        <li class="mb-2"><a href="index.php#stats" class="text-muted text-decoration-none hover-white">الإحصائيات</a></li>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
