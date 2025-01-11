<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
        <a class="navbar-brand ps-3" href="#">Edit Profile</a>
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
                        <a class="nav-link" href="<?php echo e(route('staff.dashboard')); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="<?php echo e(route('staff.calendar')); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                            My Calendar
                        </a>
                        <a class="nav-link" href="<?php echo e(route('staff.profile')); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Profile
                        </a>
                        <a class="nav-link" href="<?php echo e(route('staff.leaveApplication')); ?>">
                            <div class="sb-nav-link-icon"><i style='font-size:24px' class='fas'>&#xf7c5;</i></div>
                            Leave Application 
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo e(Auth::user()->name); ?>

                </div>
            </nav>
        </div>

        <!-- 主内容区域 -->
        <div id="layoutSidenav_content">
            <main class="container mt-4">
                <h1 class="mb-4">Leave Application History</h1>

                    <?php if($leaveApplications->isEmpty()): ?>
                        <p>No leave applications.</p>
                    <?php else: ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Submitted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $leaveApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($leave->start_date); ?></td>
                                    <td><?php echo e($leave->end_date); ?></td>
                                    <td><?php echo e($leave->reason); ?></td>
                                    <td>
                                        <?php if($leave->status === 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php elseif($leave->status === 'approved'): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($leave->created_at->format('Y-m-d H:i')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                <a href="<?php echo e(route('staff.dashboard')); ?>" class="btn btn-primary mt-3">Back to Dashboard</a>
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
</html><?php /**PATH C:\xampp\htdocs\FYP-2\SnapChat\resources\views/staff/leaveHistory.blade.php ENDPATH**/ ?>