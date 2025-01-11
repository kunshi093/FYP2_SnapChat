<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center mb-4">Attendance Records for <?php echo e($staff->name); ?></h2>

    <!-- 日历部分 -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            Staff Attendance Calendar
        </div>
        <div class="card-body">
            <div id="attendanceCalendar"></div>
        </div>
    </div>

    <!-- 出席记录表格部分 -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            Staff Attendance Records
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Clock In Time</th>
                        <th>Clock Out Time</th>
                        <th>Photo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $clockInRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($record->clock_in_time->format('Y-m-d')); ?></td>
                        <td><?php echo e($record->clock_in_time->format('H:i:s')); ?></td>
                        <td><?php echo e($record->clock_out_time ? $record->clock_out_time->format('H:i:s') : '-'); ?></td>
                        <td>
                            <?php if($record->photo): ?>
                                <a href="<?php echo e(asset('storage/' . $record->photo)); ?>" target="_blank">View Photo</a>
                            <?php else: ?>
                                No Photo
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <a href="<?php echo e(route('admin.reports')); ?>" class="btn btn-secondary mt-4">Back to Staff</a>
</div>

<!-- FullCalendar Script -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" />

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('attendanceCalendar');
        var attendanceData = <?php echo json_encode($attendanceData, 15, 512) ?>; // 后端传来的出席状态数据

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: attendanceData.map(function (item) {
                return {
                    title: item.status === 'present' ? 'Present' : 'Absent',
                    start: item.date,
                    backgroundColor: item.status === 'present' ? '#87CEFA' : '#FFB6C1',
                    borderColor: item.status === 'present' ? '#87CEFA' : '#FFB6C1',
                    textColor: '#000'
                };
            }),
            dayCellDidMount: function (info) {
                // 未来日期不显示状态
                var today = new Date().toISOString().split('T')[0];
                if (info.date.toISOString().split('T')[0] > today) {
                    info.el.style.backgroundColor = ''; // 清空颜色
                }
            },
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridMonth'
            }
        });

        calendar.render();
    });
</script>
</body>
</html><?php /**PATH C:\xampp\htdocs\FYP-2\SnapChat\resources\views/admin/viewAttendance.blade.php ENDPATH**/ ?>