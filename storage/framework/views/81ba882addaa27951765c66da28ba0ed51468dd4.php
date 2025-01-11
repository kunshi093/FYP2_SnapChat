<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapChat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; }
        .sb-topnav { position: fixed; width: 100%; top: 0; z-index: 1030; }
        .sb-sidenav { background-color: #343a40; color: #fff; width: 250px; height: 100vh; position: fixed; top: 56px; left: -250px; transition: left 0.3s; }
        .sb-sidenav.show { left: 0; }
        .sb-sidenav a { color: #fff; text-decoration: none; padding: 12px; }
        .sb-sidenav a:hover { background-color: #495057; }
        .sb-sidenav-menu-heading { font-size: 0.85rem; text-transform: uppercase; padding: 12px; }
        .sb-sidenav-footer { font-size: 0.9rem; background-color: #212529; padding: 12px; text-align: center; margin-top: auto; }
        #layoutSidenav_content { margin-left: 0; padding: 20px; margin-top: 70px; transition: margin-left 0.3s; }
        #layoutSidenav_content.shifted { margin-left: 250px; }
        .profile-photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="sb-nav-fixed">    
    <!-- 顶部导航栏 -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="#">Snap Chat</a>
        <button class="btn btn-link btn-sm" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <ul class="navbar-nav ms-auto me-3">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('login')); ?>">LogIn</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('register')); ?>">Register</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <!-- 左侧滑动菜单 -->
        <div id="layoutSidenav_nav" class="sb-sidenav">
            <nav class="accordion sb-sidenav-dark">
                <div class="sb-sidenav-menu">
                    <div class="nav flex-column">
                        <a class="nav-link" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Staff
                        </a>
                        <a class="nav-link" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                            Reports
                        </a>
                        <a class="nav-link" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                            Application Leave
                            <span id="leaveNotification" class="badge bg-danger ms-2" style="display: none;">0</span>
                        </a>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePartTime" aria-expanded="false" aria-controls="collapsePartTime">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-clock"></i></div>
                            Part-Time
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePartTime" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="#">Generate QR</a>
                                <a class="nav-link" href="#">Part-Time Staff</a>
                                <a class="nav-link" href="#">Part-Time Staff Report</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Visitor
                </div>
            </nav>
        </div>

        <!-- 主内容区域 -->
        <div id="layoutSidenav_content">
            <main class="container mt-4">
                <h1 class="text-center mb-4">Welcome to SnapChat</h1>

                <div class="card">
                    <div class="card-header bg-dark text-white">
                        About the System
                    </div>
                    <div class="card-body">
                        <p>
                            The SnapChat Attendance System is a powerful and intuitive platform designed to streamline and enhance employee attendance management. 
                            Built for small to medium-sized teams, our system provides essential features to ensure accurate timekeeping, attendance tracking, and leave management.
                        </p>
                        <h5>Key Features:</h5>
                        <ul>
                        <li><strong>Clear Employee Categorization:</strong> The system effectively distinguishes between <strong>Full-Time</strong> and <strong>Part-Time</strong> employees, offering tailored functionalities for each group.</li>
                            <li><strong>QR Code Check-In for Part-Time Staff:</strong> Part-Time employees can seamlessly check in by scanning a dynamic QR code generated daily, ensuring effortless and accurate attendance tracking.</li>
                            <li><strong>Clock-In and Clock-Out:</strong> Full-Time employees can record their work hours with just a click, making attendance tracking simple and efficient.</li>
                            <li><strong>Leave Management:</strong> Staff can apply for leave and track the status of their applications directly from their personalized dashboard.</li>
                            <li><strong>Attendance Calendar:</strong> A visual overview of attendance and leave records to help employees and administrators stay organized.</li>
                            <li><strong>Admin Dashboard:</strong> Administrators have access to advanced tools to manage attendance, view detailed reports, and handle staff profiles efficiently.</li>
                        </ul>
                        <h5>Why Choose SnapChat Attendance System?</h5>
                        <ul>
                            <li>Simple and user-friendly interface.</li>
                            <li>Reliable and accurate attendance tracking.</li>
                            <li>Customizable features tailored to your business needs.</li>
                            <li>Seamless integration for both full-time and part-time employees.</li>
                        </ul>
                        <p>
                            Join the hundreds of teams already benefiting from SnapChat Attendance System. Simplify your attendance management today!
                        </p>
                    </div>
                </div>

                <!-- QnA Section -->
                <div class="card mt-4">
                    <div class="card-header bg-dark text-white">
                        Frequently Asked Questions (Q&A)
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="qnaAccordion">
                            <!-- QnA Item 1 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="qnaHeading1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#qnaCollapse1" aria-expanded="true" aria-controls="qnaCollapse1">
                                        How does the QR code feature work for Part-Time staff?
                                    </button>
                                </h2>
                                <div id="qnaCollapse1" class="accordion-collapse collapse show" aria-labelledby="qnaHeading1" data-bs-parent="#qnaAccordion">
                                    <div class="accordion-body">
                                        Part-Time staff use a system-generated QR code to fill in their basic details, submit the form, and check in. The system automatically records their attendance.
                                    </div>
                                </div>
                            </div>
                            <!-- QnA Item 2 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="qnaHeading2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#qnaCollapse2" aria-expanded="false" aria-controls="qnaCollapse2">
                                        Can Full-Time staff use the QR code feature for check-ins?
                                    </button>
                                </h2>
                                <div id="qnaCollapse2" class="accordion-collapse collapse" aria-labelledby="qnaHeading2" data-bs-parent="#qnaAccordion">
                                    <div class="accordion-body">
                                        No, the QR code feature is exclusively designed for Part-Time staff. Full-Time staff have a dedicated Clock-In and Clock-Out button for seamless attendance tracking.
                                    </div>
                                </div>
                            </div>
                            <!-- QnA Item 3 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="qnaHeading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#qnaCollapse3" aria-expanded="false" aria-controls="qnaCollapse3">
                                        How are attendance records stored and accessed?
                                    </button>
                                </h2>
                                <div id="qnaCollapse3" class="accordion-collapse collapse" aria-labelledby="qnaHeading3" data-bs-parent="#qnaAccordion">
                                    <div class="accordion-body">
                                        Attendance records are securely stored in the system's database on the local server. Employees can view their attendance details through the My Calendar feature, while administrators can access detailed attendance reports via the Admin Dashboard.
                                    </div>
                                </div>
                            </div>
                            <!-- QnA Item 4 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="qnaHeading4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#qnaCollapse4" aria-expanded="false" aria-controls="qnaCollapse4">
                                        Is the system suitable for remote work scenarios?
                                    </button>
                                </h2>
                                <div id="qnaCollapse4" class="accordion-collapse collapse" aria-labelledby="qnaHeading4" data-bs-parent="#qnaAccordion">
                                    <div class="accordion-body">
                                        The system is not optimized for remote work. However, administrators can use a VPN to access the system remotely if necessary.
                                    </div>
                                </div>
                            </div>
                            <!-- QnA Item 5 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="qnaHeading5">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#qnaCollapse5" aria-expanded="false" aria-controls="qnaCollapse5">
                                        Where is staff data stored?
                                    </button>
                                </h2>
                                <div id="qnaCollapse5" class="accordion-collapse collapse" aria-labelledby="qnaHeading5" data-bs-parent="#qnaAccordion">
                                    <div class="accordion-body">
                                        Staff data is stored securely in the local server's database, ensuring data privacy and protection.
                                    </div>
                                </div>
                            </div>
                            <!-- QnA Item 6 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="qnaHeading6">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#qnaCollapse6" aria-expanded="false" aria-controls="qnaCollapse6">
                                        How does the system handle attendance tracking?
                                    </button>
                                </h2>
                                <div id="qnaCollapse6" class="accordion-collapse collapse" aria-labelledby="qnaHeading6" data-bs-parent="#qnaAccordion">
                                    <div class="accordion-body">
                                        Attendance tracking is done by connecting staff devices to the local network. This ensures all attendance data is recorded in the local server, eliminating risks of data breaches or external threats.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- 引入 JavaScript 文件 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('layoutSidenav_nav');
        const content = document.getElementById('layoutSidenav_content');
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            sidebar.classList.toggle('show');
            content.classList.toggle('shifted');
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\FYP-2\SnapChat\resources\views/welcome.blade.php ENDPATH**/ ?>