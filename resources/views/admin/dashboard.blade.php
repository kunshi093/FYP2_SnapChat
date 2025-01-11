<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
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
            padding: 20px;
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
        <div id="layoutSidenav_content">
            <main class="container-fluid">
                <h1 class="mt-4">Admin Dashboard</h1>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-table me-1"></i> Staff Attendance
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Clock In Time</th>
                                    <th>Clock Out Time</th>
                                    <th>Photo</th>
                                    <th>Status</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staff as $record)
                                    <tr>
                                        <td>{{ $record->name }}</td>
                                        <td>{{ $record->email }}</td>
                                        <td>{{ $record->clock_in_time ?? '-' }}</td>
                                        <td>{{ $record->clock_out_time ?? '-' }}</td>
                                        <td>
                                            @if ($record->photo)
                                                <a href="{{ asset('storage/' . $record->photo) }}" target="_blank">View Photo</a>
                                            @else
                                                No Photo
                                            @endif
                                        </td>
                                        <td>{{ $record->status }}</td> <!-- 显示状态 -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- 引入 JavaScript 文件 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script>
        const dataTable = new simpleDatatables.DataTable("#datatablesSimple");

        // 侧边栏切换
        const sidebar = document.getElementById('layoutSidenav_nav');
        const content = document.getElementById('layoutSidenav_content');
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            sidebar.classList.toggle('show');
            content.classList.toggle('shifted');
        });

        // 搜索框功能
        document.getElementById('tableSearch').addEventListener('input', function () {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#datatablesSimple tbody tr');

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchValue) ? '' : 'none';
            });
        });

        //获取通知
        function fetchNotifications() {
        console.log('Fetching notifications...');
        fetch("{{ route('admin.getNotificationCount') }}")
            .then(response => {
                console.log('Response received:', response);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Notification data:', data);
                const notificationBadge = document.getElementById('leaveNotification');
                if (data.unread_count > 0) {
                    notificationBadge.style.display = 'inline-block';
                    notificationBadge.textContent = data.unread_count; // 更新通知数量
                } else {
                    notificationBadge.style.display = 'none'; // 隐藏通知
                }
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
                alert('Failed to fetch notifications. Please check your connection.');
            });
        }

        // 页面可见性检查
        let intervalId;
        function startNotificationPolling() {
            fetchNotifications(); // 页面加载时立即获取一次通知
            intervalId = setInterval(fetchNotifications, 30000); // 每30秒获取一次通知
        }
        function stopNotificationPolling() {
            clearInterval(intervalId);
        }

        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'visible') {
                startNotificationPolling();
            } else {
                stopNotificationPolling();
            }
        });

        // 页面初始加载
        if (document.visibilityState === 'visible') {
            startNotificationPolling();
        }
    </script>
</body>
</html>