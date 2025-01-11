<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Attendance Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
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
                        <a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="<?php echo e(route('admin.viewStaff')); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Staff
                        </a>
                        <a class="nav-link" href="<?php echo e(route('admin.reports')); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                            Reports
                        </a>
                        <a class="nav-link" href="<?php echo e(route('admin.leaveRequests')); ?>">
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
                                <a class="nav-link" href="<?php echo e(route('admin.generateQR')); ?>">Generate QR</a>
                                <a class="nav-link" href="<?php echo e(route('admin.partTimeStaff')); ?>">Part-Time Staff</a>
                                <a class="nav-link" href="<?php echo e(route('admin.partTimeReport')); ?>">Part-Time Staff Report</a>
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
            <main class="container mt-4">
                <h1 class="text-center mb-4">Staff Attendance Report</h1>
                <h1 class="text-center mb-4">Attendance Report for <?php echo e(date('F', mktime(0, 0, 0, $month, 1))); ?> <?php echo e($year); ?></h1>
                <!-- Bar Chart -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Attendance Report</span>
                        <!-- 筛选月份和年份 -->
                        <form method="GET" action="<?php echo e(route('admin.reports')); ?>" class="d-flex">
                            <select name="month" class="form-select me-2" style="width: 120px;">
                                <option value="">Select Month</option>
                                <?php for($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?php echo e($m); ?>" <?php echo e($month == $m ? 'selected' : ''); ?>>
                                        <?php echo e(date('F', mktime(0, 0, 0, $m, 1))); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                            <select name="year" class="form-select me-2" style="width: 120px;">
                                <option value="">Select Year</option>
                                <?php for($y = now()->year; $y >= now()->year - 5; $y--): ?>
                                    <option value="<?php echo e($y); ?>" <?php echo e($year == $y ? 'selected' : ''); ?>>
                                        <?php echo e($y); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <canvas id="attendanceChart" width="400" height="150"></canvas>
                    </div>
                </div>

                <!-- 表格区域 -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Staff Attendance Details</span>
                        <!-- 搜索栏 -->
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" id="tableSearch" placeholder="Search for staff...">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover" id="attendanceTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Attendance Days</th>
                                    <th>Absence Days</th>
                                    <th>On Leave Dyas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $staffNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo e(route('admin.viewStaffAttendance', $staffIds[$index])); ?>">
                                                <?php echo e($name); ?>

                                            </a>
                                        </td>
                                        <td><?php echo e($staffEmails[$index]); ?></td>
                                        <td><?php echo e($attendanceDays[$index]); ?></td>
                                        <td><?php echo e($absenceDays[$index]); ?></td>
                                        <td><?php echo e($leaveDays[$index]); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- JavaScript 引入 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"></script>
    <script>
        // 侧边栏切换
        const sidebar = document.getElementById('layoutSidenav_nav');
        const content = document.getElementById('layoutSidenav_content');
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            sidebar.classList.toggle('show');
            content.classList.toggle('shifted');
        });
        
        //bar chart
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('attendanceChart').getContext('2d');

            const labels = <?php echo json_encode($staffNames, 15, 512) ?>;
            const attendanceDays = <?php echo json_encode($attendanceDays, 15, 512) ?>;
            const leaveDays = <?php echo json_encode($leaveDays, 15, 512) ?>;
            const absenceDays = <?php echo json_encode($absenceDays, 15, 512) ?>;
            const daysUntilToday = <?php echo json_encode($daysUntilToday, 15, 512) ?>; // 当前日期之前的天数

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Attendance',
                            data: attendanceDays,
                            backgroundColor: '#4CAF50',
                        },
                        {
                            label: 'On Leave',
                            data: leaveDays,
                            backgroundColor: '#FFC107',
                        },
                        {
                            label: 'Absence',
                            data: absenceDays,
                            backgroundColor: '#F44336',
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                    },
                    scales: {
                        x: { stacked: true },
                        y: {
                            stacked: true,
                            suggestedMax: daysUntilToday, // 设置为当月的实际天数（到今天为止）
                            ticks: {
                                beginAtZero: true,
                                stepSize: 1,
                            },
                        },
                    },
                },
            });
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\FYP-2\SnapChat\resources\views/admin/report.blade.php ENDPATH**/ ?>