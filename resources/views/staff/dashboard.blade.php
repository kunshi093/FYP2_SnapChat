<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            overflow-x: hidden;
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
            left: -250px;
            transition: left 0.3s ease-in-out;
        }
        .sb-sidenav-footer {
            font-size: 0.9rem;
            background-color: #212529;
            padding: 12px;
            text-align: center;
        }
        .sb-sidenav.show {
            left: 0;
        }
        .sb-sidenav a {
            color: #fff;
            text-decoration: none;
            padding: 12px;
        }
        .sb-sidenav a:hover {
            background-color: #495057;
        }
        #layoutSidenav_content {
            margin-left: 0;
            padding: 20px;
            margin-top: 70px;
            transition: margin-left 0.3s ease-in-out;
        }
        #layoutSidenav_content.shifted {
            margin-left: 250px;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <!-- 顶部导航栏 -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="#">Staff Dashboard</a>
        <button class="btn btn-link btn-sm" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <ul class="navbar-nav ms-auto me-3">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('staff.profile') }}">Profile</a></li>
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
    <!-- 左侧滑动菜单 -->
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav" class="sb-sidenav">
            <nav class="accordion sb-sidenav-dark">
                <div class="sb-sidenav-menu">
                    <div class="nav flex-column">
                        <a class="nav-link" href="{{ route('staff.dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('staff.calendar') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                            My Calendar
                        </a>
                        <a class="nav-link" href="{{ route('staff.profile') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Profile
                        </a>
                        <a class="nav-link" href="{{ route('staff.leaveApplication') }}">
                            <div class="sb-nav-link-icon"><i style='font-size:24px' class='fas'>&#xf7c5;</i></div>
                            Leave Application 
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    {{ Auth::user()->name }}
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main class="container-fluid">
                <h1 class="mt-4">Staff Dashboard</h1>

                <!-- 卡片内容 -->
                <div class="row g-3 mb-4">
                    <!-- 左侧列：上下排列 Clock In 和 Clock Out -->
                    <div class="col-md-4 d-flex flex-column gap-3">
                        <!-- Clock In -->
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <button id="clockInBtn" class="btn btn-light btn-lg">Clock In</button>
                            </div>
                        </div>
                        <audio id="clockInSound" src="{{ asset('sounds/clock-in.mp3') }}"></audio>
                        <!-- Clock Out -->
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <button id="clockOutBtn" class="btn btn-light btn-lg">Clock Out</button>
                            </div>
                        </div>
                    </div>
                    <audio id="clockOutSound" src="{{ asset('sounds/clock-out.mp3') }}"></audio>

                    <!-- 右侧列：水平排列 Leave Application 和 Apply for Leave -->
                    <div class="col-md-8">
                        <div class="d-flex gap-3 align-items-stretch"> <!-- 添加 align-items-stretch -->
                            <!-- Leave Application -->
                            <div class="card bg-info text-white flex-fill">
                                <div class="card-body d-flex flex-column"> <!-- 添加 flex-column -->
                                    <h5 class="card-title">Leave Application</h5>
                                    @if ($currentLeave)
                                        <p>
                                            <strong>Status:</strong>
                                            @if ($currentLeave->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif ($currentLeave->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </p>
                                        <p><strong>From:</strong> {{ $currentLeave->start_date }}</p>
                                        <p><strong>To:</strong> {{ $currentLeave->end_date }}</p>
                                    @else
                                        <p>No current leave applications.</p>
                                    @endif
                                    <p><strong>Total Leave Days:</strong> {{ $totalLeaveDays }} days</p> <!-- 显示总请假天数 -->
                                    <div class="mt-auto"> <!-- 将 View Leave History 放到底部 -->
                                        <a href="{{ route('staff.leaveHistory') }}" class="text-white">View Leave History</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Warning Card -->
                            <div class="card bg-secondary text-white flex-fill">
                                <div class="card-body d-flex flex-column text-center"> <!-- 添加 flex-column -->
                                    <h5 class="card-title">Attendance Warning</h5>
                                    <p id="attendanceInfo" class="mt-auto">Checking attendance data...</p> <!-- 调整内容对齐 -->
                                    <audio id="alertSound" src="/path/to/alert-sound.mp3"></audio> <!-- 提示音文件 -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- PAI表示例 -->
                <div class="card mb-1">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-1"></i>
                        Attendance Status (Pie Chart)
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-start">
                        <!-- Pie Chart -->
                        <div style="width: 390px; height: 390px;">
                            <canvas id="attendancePieChart"></canvas>
                        </div>

                        <!-- 统计表格 -->
                        <div class="ms-100">
                            <h5 class="mb-10">Attendance Statistics</h5>
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Status</th>
                                        <th>Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Present</td>
                                        <td>{{ $attendanceDays }} days</td>
                                    </tr>
                                    <tr>
                                        <td>Absent</td>
                                        <td>{{ $absenceDays }} days</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 打卡记录表格 -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Clock-In Records
                    </div>
                    <div class="card-body">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Clock In Time</th>
                                    <th>Clock Out Time</th>
                                    <th>Photo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clockInRecords as $record)
                                <tr>
                                    <td>{{ $record->clock_in_time ? $record->clock_in_time->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $record->clock_in_time ? $record->clock_in_time->format('h:i A') : '-' }}</td>
                                    <td>{{ $record->clock_out_time ? $record->clock_out_time->format('h:i A') : '-' }}</td>
                                    <td>
                                        @if($record->photo)
                                            <a href="{{ asset('storage/' . $record->photo) }}" target="_blank" class="btn btn-success btn-sm">
                                                View Photo
                                            </a>
                                        @else
                                            <form action="{{ route('staff.uploadPhoto', $record->id) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center justify-content-center">
                                                @csrf
                                                <input type="file" name="photo" class="form-control form-control-sm me-2" style="width: auto;" required>
                                                <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const sidebar = document.getElementById('layoutSidenav_nav');
        const content = document.getElementById('layoutSidenav_content');
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            sidebar.classList.toggle('show');
            content.classList.toggle('shifted');
        });

        //PaiChart
        const pieCtx = document.getElementById('attendancePieChart').getContext('2d');

        // 数据从后端传递：出勤天数、缺勤天数
        const attendanceData = {
            present: {{ $attendanceDays }},
            absent: {{ $absenceDays }},

        };

        const attendancePieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    data: [attendanceData.present, attendanceData.absent],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)', // Present: Blue
                        'rgba(255, 99, 132, 0.6)', // Absent: Pink

                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',

                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const value = context.raw;
                                const percentage = ((value / total) * 100).toFixed(2);
                                return `${context.label}: ${value} days (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // 处理打卡请求并更新状态
        document.getElementById('clockInBtn').addEventListener('click', function() {
            console.log('Clock In button clicked');
            fetch('{{ route("staff.clockIn") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Clocked in successfully at: ' + data.time);
                    clockInSound.play();
                        setTimeout(() => location.reload(), 2000); // 延迟 1 秒刷新
                } else {
                    alert(data.message || 'Clock-in failed!');
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('clockOutBtn').addEventListener('click', function() {
            console.log('Clock Out button clicked');
            fetch('{{ route("staff.clockOut") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Clocked out successfully at: ' + data.time);
                    clockOutSound.play();
                        setTimeout(() => location.reload(), 1500); // 延迟 1 秒刷新
                } else {
                    alert(data.message || 'Clock-out failed!');
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.addEventListener("DOMContentLoaded", function () {
            const absenceDays = {{ $absenceDays }};
            const attendanceRecords = {{ $attendanceDays }};
            const attendanceInfo = document.getElementById("attendanceInfo");
            const alertSound = document.getElementById("alertSound");

            if (absenceDays >= 3) {
                // 动态修改卡片信息
                attendanceInfo.innerHTML = `
                    <strong>Warning!</strong> You have missed ${absenceDays} days.
                    You have attended ${attendanceRecords} days this month.`;
                
                // 根据缺勤天数显示不同的警告颜色
                const warningCard = attendanceInfo.closest(".card");
                if (absenceDays >= 10) {
                    warningCard.classList.replace("bg-secondary", "bg-danger"); // 红色
                } else if (absenceDays >= 7) {
                    warningCard.classList.replace("bg-secondary", "bg-warning"); // 黄色
                } else if (absenceDays >= 3) {
                    warningCard.classList.replace("bg-secondary", "bg-info"); // 蓝色
                }

                // 播放提示音
                alertSound.play();
            } else {
                // 正常状态
                attendanceInfo.innerHTML = `
                    <strong>Good job!</strong> You have attended ${attendanceRecords} days this month.`;
            }
        });
    </script>
</body>
</html> 
