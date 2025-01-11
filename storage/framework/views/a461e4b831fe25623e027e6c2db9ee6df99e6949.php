<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; }
        .sb-topnav { position: fixed; width: 100%; top: 0; z-index: 1030; }
        .sb-sidenav { background-color: #343a40; color: #fff; width: 250px; height: 100vh; position: fixed; top: 56px; left: -250px; transition: left 0.3s; }
        .sb-sidenav.show { left: 0; }
        .sb-sidenav a { color: #fff; text-decoration: none; padding: 12px; }
        .sb-sidenav a:hover { background-color: #495057; }
        .sb-sidenav-menu-heading { font-size: 0.85rem; text-transform: uppercase; padding: 12px; margin-bottom: 0; }
        .sb-sidenav-footer { font-size: 0.9rem; background-color: #212529; padding: 12px; text-align: center; margin-top: auto; }
        #layoutSidenav_content { margin-left: 0; padding: 20px; margin-top: 70px; transition: margin-left 0.3s; }
        #layoutSidenav_content.shifted { margin-left: 250px; }
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
        <!-- 侧边栏导航 -->
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
            <main class="container-fluid">
                <h2 class="mt-4">Staff Members</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $staffMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <!-- 修改链接指向个人主页 -->
                                    <a href="<?php echo e(route('admin.viewStaffProfile', $staff->id)); ?>">
                                        <?php echo e($staff->name); ?>

                                    </a>
                                </td>
                                <td><?php echo e($staff->email); ?></td>
                                <td>
                                    <a href="<?php echo e(route('admin.editStaff', $staff->id)); ?>" class="btn btn-primary btn-sm me-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="<?php echo e(route('admin.deleteStaff', $staff->id)); ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
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
</html><?php /**PATH C:\xampp\htdocs\FYP-2\SnapChat\resources\views/admin/viewStaff.blade.php ENDPATH**/ ?>