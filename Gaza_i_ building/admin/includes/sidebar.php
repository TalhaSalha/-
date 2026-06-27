<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar glass-sidebar">
    <div class="d-flex flex-column h-100">
        <!-- Logo -->
        <a href="index.php" class="sidebar-brand text-decoration-none d-flex align-items-center justify-content-center py-4">
            <div class="sidebar-logo-icon me-2">
                <i class="fas fa-hammer text-gold"></i>
            </div>
            <h3 class="m-0 text-gold fw-bold" style="font-family: 'Cairo', sans-serif;">غزة تبني</h3>
        </a>

        <!-- Profile Section -->
        <div class="sidebar-profile px-4 mb-4">
            <div class="d-flex align-items-center p-3 glass-profile-card">
                <div class="position-relative">
                    <div class="profile-img-container">
                        <i class="fas fa-user text-dark"></i>
                    </div>
                    <div class="status-indicator bg-success"></div>
                </div>
                <div class="me-3 overflow-hidden">
                    <h6 class="mb-0 text-white text-truncate fw-bold">المسؤول</h6>
                    <small class="text-muted text-truncate d-block">Admin Panel</small>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="navbar-nav w-100 px-3 gap-2 flex-grow-1">
            <span class="nav-header text-muted small fw-bold px-3 mb-2 mt-2">القائمة الرئيسية</span>
            
            <a href="index.php" class="nav-item nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                <span class="nav-text">لوحة التحكم</span>
            </a>
            
            <a href="users.php" class="nav-item nav-link <?php echo $current_page == 'users.php' ? 'active' : ''; ?>">
                <span class="nav-icon"><i class="fas fa-users-cog"></i></span>
                <span class="nav-text">المستخدمين</span>
            </a>
            
            <a href="messages.php" class="nav-item nav-link <?php echo $current_page == 'messages.php' ? 'active' : ''; ?>">
                <span class="nav-icon"><i class="fas fa-envelope"></i></span>
                <span class="nav-text">الرسائل</span>
                <!-- Optional: Badge for unread messages could go here -->
            </a>

            <span class="nav-header text-muted small fw-bold px-3 mb-2 mt-4">النظام</span>

            <a href="settings.php" class="nav-item nav-link <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                <span class="nav-icon"><i class="fas fa-sliders-h"></i></span>
                <span class="nav-text">إعدادات الموقع</span>
            </a>
            
            <a href="profile.php" class="nav-item nav-link <?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                <span class="nav-icon"><i class="fas fa-user-shield"></i></span>
                <span class="nav-text">الملف الشخصي</span>
            </a>
        </nav>

        <!-- Footer / Logout -->
        <div class="sidebar-footer p-3">
            <a href="logout.php" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="fas fa-sign-out-alt"></i>
                <span>تسجيل الخروج</span>
            </a>
        </div>
    </div>
</div>
