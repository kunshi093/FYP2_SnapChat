<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Leave Requests</h2>
    <table class="table table-bordered">
        <thead class="table-dark">
        <tr>
            <th>Staff Name</th>
            <th>Reason</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Document</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $leaveRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($request->staff->name); ?></td>
                <td><?php echo e($request->reason); ?></td>
                <td><?php echo e($request->start_date); ?></td>
                <td><?php echo e($request->end_date); ?></td>
                <td>
                    <?php if($request->photo): ?>
                        <a href="<?php echo e(asset('storage/' . $request->photo)); ?>" target="_blank">View Document</a>
                    <?php else: ?>
                        No Document
                    <?php endif; ?>
                </td>
                <td><?php echo e(ucfirst($request->status)); ?></td>
                <td>
                    <?php if($request->status === 'pending'): ?>
                        <form action="<?php echo e(route('admin.approveLeave', $request->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <form action="<?php echo e(route('admin.rejectLeave', $request->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    <?php else: ?>
                        <span class="text-muted">No Actions Available</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">Back to Dashboard</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\xampp\htdocs\FYP-2\SnapChat\resources\views/admin/leaveRequests.blade.php ENDPATH**/ ?>