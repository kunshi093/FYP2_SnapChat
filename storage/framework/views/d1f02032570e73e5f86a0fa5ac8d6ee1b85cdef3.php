<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center mb-4">Attendance Calendar for <?php echo e($user->name); ?></h2>

    <!-- 日历部分 -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            My Attendance Calendar
        </div>
        <div class="card-body">
            <div id="attendanceCalendar"></div>
        </div>
    </div>
    <div class="d-flex justify-content-center mb-3">
        <span class="badge bg-info text-dark me-3">Present</span>
        <span class="badge bg-danger text-light">Absent</span>
    </div>

    <a href="<?php echo e(route('staff.dashboard')); ?>" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>

<!-- FullCalendar Script -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" />

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('attendanceCalendar');
        var attendanceData = <?php echo json_encode($attendanceData, 15, 512) ?>;

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: attendanceData.map(function (item) {
                let color;
                if (item.status === 'present') color = '#68fad9'; // 蓝色: Present
                else if (item.status === 'on leave') color = '#87CEFA'; // 薄荷蓝: On Leave
                else color = '#FFB6C1'; // 粉色: Absent

                return {
                    title: item.status === 'present' ? 'Present' : item.status === 'on leave' ? 'On Leave' : 'Absent',
                    start: item.date,
                    backgroundColor: color,
                    borderColor: color,
                    textColor: '#000',
                };
            }),
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridMonth',
            },
        });

        calendar.render();
    });
</script>
</body>
</html><?php /**PATH C:\xampp\htdocs\FYP-2\SnapChat\resources\views/staff/calendar.blade.php ENDPATH**/ ?>