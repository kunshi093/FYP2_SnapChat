<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            overflow-x: hidden; /* 防止页面横向滚动 */
        }

        .sb-topnav {
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1030;
        }

        .sb-sidenav {
            background-color: #343a40;
            color: #fff;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 56px;
            left: -250px; /* 初始隐藏在左侧 */
            transition: left 0.3s ease-in-out; /* 菜单滑动动画 */
        }

        .sb-sidenav.show {
            left: 0; /* 显示时从左侧滑出 */
        }

        .sb-sidenav a {
            color: #fff;
            text-decoration: none;
            padding: 12px;
        }

        .sb-sidenav a:hover {
            background-color: #495057;
        }

        .sb-sidenav-menu-heading {
            font-size: 0.85rem;
            text-transform: uppercase;
            padding: 12px;
        }

        .sb-sidenav-footer {
            font-size: 0.9rem;
            background-color: #212529;
            padding: 12px;
            text-align: center;
        }

        #layoutSidenav_content {
            margin-left: 0;
            margin-top: 70px;
            transition: margin-left 0.3s ease-in-out; /* 内容区域滑动动画 */
        }

        #layoutSidenav_content.shifted {
            margin-left: 250px; /* 滑动偏移 */
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <!-- 顶部导航栏 -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="#">SnapChat Admin</a>
        <button class="btn btn-link btn-sm" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <ul class="navbar-nav ms-auto me-3">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item" type="submit">Logout</button>
                        </form>
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
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('admin.viewStaff') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Staff
                        </a>
                        <a class="nav-link" href="{{ route('admin.reports') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                            Reports
                        </a>
                        <a class="nav-link" href="{{ route('admin.leaveRequests') }}">
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
                                <a class="nav-link" href="{{ route('admin.generateQR') }}">Generate QR</a>
                                <a class="nav-link" href="{{ route('admin.partTimeStaff') }}">Part-Time Staff</a>
                                <a class="nav-link" href="{{ route('admin.partTimeReport') }}">Part-Time Staff Report</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Admin
                </div>
            </nav>
        </div>

        <!-- 主内容区域 -->
        <div id="layoutSidenav_content" class="p-4">
        <div class="container mt-4">
            <h2 class="text-center mb-4">Generate QR Code</h2>
            <div class="card">
                <div class="card-body">
                    <p>Click the button below to generate a new QR code for part-time staff registration.</p>
                    <button class="btn btn-primary" id="generateQRCodeBtn">Generate QR Code</button>
                    
                    <!-- 显示假的 QR 码 -->
                    <div id="qrCodeContainer" class="mt-4 text-center" style="display: none;">
                        <img 
                            id="fakeQRCode" 
                            src="https://i.pinimg.com/originals/60/c1/4a/60c14a43fb4745795b3b358868517e79.png" 
                            alt="Fake QR Code" 
                            style="cursor: pointer;"
                        />
                        <p class="mt-3">
                            <strong>QR Code URL:</strong>
                            <a id="qrCodeLink" href="#" target="_blank" class="d-block"></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 侧边栏切换
        const sidebar = document.getElementById('layoutSidenav_nav');
        const content = document.getElementById('layoutSidenav_content');
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            sidebar.classList.toggle('show');
            content.classList.toggle('shifted');
        });

        // 生成假的 QR 码
        document.getElementById('generateQRCodeBtn').addEventListener('click', function () {
            const qrCodeContainer = document.getElementById('qrCodeContainer');
            const qrCodeLink = document.getElementById('qrCodeLink');
            const fakeQRCode = document.getElementById('fakeQRCode');
            const qrCodeUrl = '{{ route("partTime.register") }}'; // Part-Time 注册页面的 URL

            // 设置假的二维码链接和显示容器
            qrCodeLink.textContent = qrCodeUrl;
            qrCodeLink.href = qrCodeUrl;
            fakeQRCode.addEventListener('click', function () {
                window.location.href = qrCodeUrl; // 点击假二维码跳转到注册页面
            });
            qrCodeContainer.style.display = 'block'; // 显示 QR 码容器
        });
    </script>
</body>
</html>