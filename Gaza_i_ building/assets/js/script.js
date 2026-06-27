document.addEventListener('DOMContentLoaded', function () {
    // Toast Function (Global)
    function showToast(message) {
        const toastEl = document.getElementById('liveToast');
        const toastBody = document.getElementById('toastMessage');
        if (toastEl && toastBody) {
            toastBody.textContent = message;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    }

    const form = document.getElementById('assessmentForm');
    
    // Only run if the form exists (on request.php)
    if (form) {
        let currentStep = 1;
        const totalSteps = 5;
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const submitBtn = document.getElementById('submitBtn');
        const progressLine = document.querySelector('.progress-line');

        function updateStep() {
            // Show/Hide Steps with Fade Effect
            document.querySelectorAll('.step-content').forEach(el => {
                el.classList.remove('active');
                el.style.display = 'none'; // Hide completely first
            });
            
            const activeStep = document.getElementById(`step${currentStep}`);
            activeStep.style.display = 'block';
            // Trigger reflow to enable transition
            void activeStep.offsetWidth; 
            activeStep.classList.add('active');

            // Update Indicators
            document.querySelectorAll('.step-modern').forEach(el => {
                const stepNum = parseInt(el.getAttribute('data-step'));
                if (stepNum === currentStep) {
                    el.classList.add('active');
                    el.classList.remove('completed');
                } else if (stepNum < currentStep) {
                    el.classList.add('completed');
                    el.classList.remove('active');
                } else {
                    el.classList.remove('active', 'completed');
                }
            });

            // Buttons State
            prevBtn.disabled = currentStep === 1;
            
            if (currentStep === totalSteps) {
                nextBtn.classList.add('d-none');
                submitBtn.classList.remove('d-none');
                populateReview();
            } else {
                nextBtn.classList.remove('d-none');
                submitBtn.classList.add('d-none');
                nextBtn.innerHTML = 'التالي <i class="fas fa-arrow-left ms-2"></i>';
            }
            
            // Scroll to top of form
            form.closest('.form-container-glass').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function validateStep() {
            const currentStepEl = document.getElementById(`step${currentStep}`);
            const inputs = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                    // Add shake animation
                    input.style.animation = 'shake 0.5s';
                    setTimeout(() => input.style.animation = '', 500);
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            return isValid;
        }

        function populateReview() {
            const reviewList = document.getElementById('reviewList');
            const formData = new FormData(form);
            let html = '';

            const labels = {
                'owner_id': 'رقم الهوية',
                'building_id_check': 'رقم المبنى',
                'submitter_name': 'الاسم',
                'submitter_phone': 'الهاتف',
                'submitter_email': 'البريد',
                'building_name': 'اسم المبنى',
                'building_type': 'نوع المبنى',
                'address': 'العنوان',
                'damage_type': 'نوع الضرر',
                'description': 'وصف الضرر',
                'additional_info': 'ملاحظات'
            };

            for (let [key, value] of formData.entries()) {
                if (labels[key] && value instanceof File === false && value.trim() !== '') {
                    let displayValue = value;
                    
                    if (key === 'building_type') {
                        displayValue = value === 'government' ? 'حكومي' : 'خاص';
                    } else if (key === 'damage_type') {
                        displayValue = value === 'total' ? 'كلي' : 'جزئي';
                    }

                    html += `<li class="list-group-item bg-transparent text-white border-secondary"><strong class="text-gold ms-2">${labels[key]}:</strong> ${displayValue}</li>`;
                } else if (value instanceof File && value.name) {
                    html += `<li class="list-group-item bg-transparent text-white border-secondary"><strong class="text-gold ms-2">صورة المبنى:</strong> ${value.name}</li>`;
                }
            }
            reviewList.innerHTML = html;
        }

        function loadBuildingData(b) {
            document.querySelector('[name="submitter_name"]').value = b.submitter_name;
            document.querySelector('[name="submitter_phone"]').value = b.submitter_phone;
            document.querySelector('[name="submitter_email"]').value = b.submitter_email;
            document.querySelector('[name="building_name"]').value = b.building_name;
            document.querySelector('[name="building_type"]').value = b.building_type;
            document.querySelector('[name="address"]').value = b.address;
            document.querySelector('[name="description"]').value = b.description || '';
            document.querySelector('[name="additional_info"]').value = b.additional_info || '';
            
            if (b.damage_type === 'total') {
                document.getElementById('damageTotal').checked = true;
            } else {
                document.getElementById('damagePartial').checked = true;
            }

            // Set mode to update
            document.getElementById('requestType').value = 'update';
            document.getElementById('existingId').value = b.id;
        }

        function checkBuilding() {
            const ownerId = document.getElementById('ownerIdInput').value;
            const buildingId = document.getElementById('buildingIdInput').value;
            const nextButton = document.getElementById('nextBtn');
            
            // If NO building ID provided, assume New Request and skip check
            if (!buildingId) {
                document.getElementById('requestType').value = 'create';
                document.getElementById('existingId').value = '';
                currentStep++;
                updateStep();
                return;
            }

            const originalText = nextButton.innerHTML;
            nextButton.disabled = true;
            nextButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحقق...';

            const formData = new FormData();
            formData.append('owner_id', ownerId);
            formData.append('building_id', buildingId);

            fetch('check_building.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                nextButton.disabled = false;
                nextButton.innerHTML = originalText;

                if (data.success) {
                    if (data.found) {
                        // Show Confirmation Modal
                        const modalEl = document.getElementById('confirmEditModal');
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();

                        // Handle Confirmation
                        const confirmBtn = document.getElementById('confirmEditBtn');
                        // Remove previous listeners to avoid duplicates
                        const newConfirmBtn = confirmBtn.cloneNode(true);
                        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
                        
                        newConfirmBtn.addEventListener('click', function() {
                            modal.hide();
                            loadBuildingData(data.data);
                            currentStep++;
                            updateStep();
                        });
                    } else {
                        alert('لم يتم العثور على طلب بهذا الرقم. يرجى التأكد من البيانات.');
                    }
                } else {
                    alert('خطأ: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء التحقق.');
                nextButton.disabled = false;
                nextButton.innerHTML = originalText;
            });
        }

        nextBtn.addEventListener('click', () => {
            if (validateStep()) {
                if (currentStep === 1) {
                    checkBuilding();
                } else if (currentStep < totalSteps) {
                    currentStep++;
                    updateStep();
                }
            } else {
                alert('يرجى ملء جميع الحقول المطلوبة المميزة باللون الأحمر.');
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                updateStep();
            }
        });

        submitBtn.addEventListener('click', () => {
            const formData = new FormData(form);
            
            // Disable button to prevent multiple submissions
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> جاري الإرسال...';

            fetch('submit_request.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show Toast
                    const requestType = document.getElementById('requestType').value;
                    const msg = requestType === 'update' ? 'تم تعديل البيانات بنجاح' : 'تم إضافة الطلب بنجاح';
                    showToast(msg);

                    // Success Message Popup
                    const buildingId = data.building_id || '---';
                    document.querySelector('.form-container-glass').innerHTML = `
                        <div class="text-center py-5 animate-fade-in-up">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success fa-5x" style="filter: drop-shadow(0 0 20px rgba(25, 135, 84, 0.5));"></i>
                            </div>
                            <h2 class="mb-3 text-white fw-bold">تم إضافة المبنى بنجاح!</h2>
                            <p class="lead text-muted mb-4">
                                سيتم تقييم المبنى من قبل الطواقم المختصة وإرسال حالة المبنى على البريد الإلكتروني المسجل.
                            </p>
                            <div class="alert alert-dark d-inline-block border-secondary text-gold mb-5">
                                <i class="fas fa-info-circle me-2"></i>
                                تم إدراج هذا المبنى ضمن إحصائيات المباني المدمرة في قطاع غزة.
                            </div>
                            <br>
                            <a href="index.php" class="btn btn-premium btn-lg">العودة للرئيسية</a>
                        </div>
                    `;
                } else {
                    alert('حدث خطأ: ' + data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>إرسال الطلب';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء الاتصال بالخادم.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>إرسال الطلب';
            });
        });

        // Remove invalid class on input
        form.addEventListener('input', (e) => {
            if (e.target.classList.contains('is-invalid')) {
                e.target.classList.remove('is-invalid');
            }
        });

        // Initialize Stepper
        updateStep();
    }

    // Contact Form Handling (Home Page)
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الإرسال...';
            submitBtn.disabled = true;

            const formData = new FormData(this);

            fetch('submit_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم الإرسال!',
                        text: 'شكراً لتواصلك معنا، سنقوم بالرد عليك في أقرب وقت.',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#d4af37', // Gold color
                        background: '#1e293b',
                        color: '#fff'
                    });
                    this.reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: data.message || 'حدث خطأ غير متوقع',
                        confirmButtonText: 'حاول مرة أخرى',
                        confirmButtonColor: '#ef4444',
                        background: '#1e293b',
                        color: '#fff'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ في الاتصال',
                    text: 'تأكد من اتصالك بالإنترنت وحاول مرة أخرى',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#ef4444',
                    background: '#1e293b',
                    color: '#fff'
                });
                console.error('Error:', error);
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    // Animation on scroll for Index page
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    }});

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
                // Add staggered animation for children if it's a row
                if (entry.target.classList.contains('row')) {
                    const children = entry.target.children;
                    for (let i = 0; i < children.length; i++) {
                        children[i].style.animationDelay = `${i * 100}ms`;
                    }
                }
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-card, .step-box, .visual-card, .about-text, .hero-content').forEach(el => {
        el.style.opacity = '0'; // Hide initially
        // Reset animation for observer
        el.classList.remove('animate-fade-in-up'); 
        observer.observe(el);
    });
    
    // Parallax Effect for Hero
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.hero-section');
        if (hero) {
            hero.style.backgroundPositionY = -(scrolled * 0.5) + 'px';
        }
    });

// Add Shake Animation Style
const styleSheet = document.createElement("style");
styleSheet.innerText = `
@keyframes shake {
  0% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  50% { transform: translateX(5px); }
  75% { transform: translateX(-5px); }
  100% { transform: translateX(0); }
}
`;
document.head.appendChild(styleSheet);