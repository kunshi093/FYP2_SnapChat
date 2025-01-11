<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ClockIn;
use App\Models\Leave;
use App\Models\LeaveRequest;
use App\Models\PartTimeStaff;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
{
    $staff = DB::table('users')
        ->where('role', 'staff')
        ->leftJoin('clock_ins', function ($join) {
            $join->on('users.id', '=', 'clock_ins.user_id')
                ->whereDate('clock_ins.clock_in_time', now()->toDateString()); // 获取当天打卡记录
        })
        ->select('users.*', 'clock_ins.clock_in_time', 'clock_ins.clock_out_time', 'clock_ins.photo')
        ->get()
        ->map(function ($record) {
            // 检查员工是否正在请假（从 leave_requests 表中查询）
            $onLeave = DB::table('leave_requests')
                ->where('user_id', $record->id)
                ->where('status', 'approved') // 仅检查已批准的请假
                ->whereDate('start_date', '<=', now()->toDateString())
                ->whereDate('end_date', '>=', now()->toDateString())
                ->exists();

            // 计算员工状态
            if ($record->clock_in_time) {
                $record->status = 'Present'; // 如果有 Clock In 时间，则为 Present
            } elseif ($onLeave) {
                $record->status = 'On Leave'; // 如果在请假期间，则为 On Leave
            } else {
                $record->status = 'Absent'; // 默认缺席
            }

            return $record;
        });

    // 将数据传递到视图
    return view('admin.dashboard', compact('staff'));
}
//------------------------------------------------------------------------------------------------------
    //显示 staff 的资料
    public function viewStaff()
    {
        // 确保 `Staff` 模型存在并且能被导入
        $staffMembers = User::where('role', 'staff')->get(); // 或者 User::where('role', 'staff')->get()
        return view('admin.viewStaff', compact('staffMembers'));
    }

    //view attendance
    public function viewStaffAttendance($id)
    {
        // 查询特定 staff 的信息
        $staff = User::where('id', $id)->where('role', 'staff')->firstOrFail();

        // 获取该 staff 的所有打卡记录
        $clockInRecords = ClockIn::where('user_id', $id)
            ->whereDate('clock_in_time', '>=', $staff->created_at->toDateString()) // 从注册日期开始
            ->orderBy('clock_in_time', 'desc')
            ->get();

        // 生成日历所需的数据 (仅到今天的状态)
        $attendanceData = [];
        $startDate = $staff->created_at->toDateString();
        $today = now()->toDateString();

        $period = \Carbon\CarbonPeriod::create($startDate, $today);

        foreach ($period as $date) {
            $formattedDate = $date->toDateString();
            $attendanceData[] = [
                'date' => $formattedDate,
                'status' => $clockInRecords->contains(function ($record) use ($formattedDate) {
                    return $record->clock_in_time->toDateString() == $formattedDate;
                }) ? 'present' : 'absent',
            ];
        }

        return view('admin.viewAttendance', compact('staff', 'clockInRecords', 'attendanceData'));
    }
//------------------------------------------------------------------------------------------------------
//看 staff的资料
    public function viewStaffProfile($id)
    {
        // 获取指定的员工
        $staff = User::findOrFail($id);

        // 返回个人主页视图
        return view('admin.staffProfile', compact('staff'));
    }


// 修改 staff 资料
    public function editStaff($id)
    {
        // 查询 role 为 'staff' 的用户，并根据 id 查找
        $staff = User::where('role', 'staff')->where('id', $id)->firstOrFail();

        // 确保找到的用户数据后再进行其他操作
        return view('admin.editStaff', compact('staff'));
    }

    public function updateStaff(Request $request, $id)
    {
    $staff = User::where('role', 'staff')->where('id', $id)->firstOrFail();
    
    // 验证输入
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    // 更新员工信息
    $staff->name = $request->name;
    $staff->email = $request->email;

    // 如果密码不为空，则更新密码
    if ($request->filled('password')) {
        $staff->password = bcrypt($request->password);
    }

    $staff->save();

    return redirect()->route('admin.viewStaff')->with('success', 'Staff updated successfully.');
    }

    //------------------------------------------------------------------------------------------------------
    // 删除 staff 资料
    public function deleteStaff($id)
    {
        $staff = User::where('role', 'staff')->where('id', $id)->firstOrFail();
        $staff->delete();
        return redirect()->route('admin.viewStaff')->with('success', 'Staff deleted successfully.');
    }

    //------------------------------------------------------------------------------------------------------
    //Staff的出席率
    public function report(Request $request)
    {
        $month = $request->input('month', now()->month); // 默认当前月份
        $year = $request->input('year', now()->year);    // 默认当前年份
        $today = Carbon::now(); // 获取当前日期
        $daysUntilToday = $today->day; // 只统计当前日期之前的天数

        $staff = User::where('role', 'staff')->get(); // 获取所有员工
        $staffNames = $staff->pluck('name')->toArray();
        $staffEmails = $staff->pluck('email')->toArray();
        $staffIds = $staff->pluck('id')->toArray();

        $attendanceDays = [];
        $absenceDays = [];
        $leaveDays = []; // 请假天数

        foreach ($staff as $employee) {
            // 出勤天数
            $attendances = ClockIn::where('user_id', $employee->id)
                ->whereYear('clock_in_time', $year)
                ->whereMonth('clock_in_time', $month)
                ->whereDay('clock_in_time', '<=', $daysUntilToday)
                ->count();

            // 请假天数
            $leaves = LeaveRequest::where('user_id', $employee->id)
                ->where('status', 'approved')
                ->where(function ($query) use ($year, $month) {
                    $query->whereYear('start_date', $year)
                        ->whereMonth('start_date', $month)
                        ->orWhereYear('end_date', $year)
                        ->whereMonth('end_date', $month);
                })
                ->get();

            $totalLeaveDays = $leaves->reduce(function ($carry, $leave) use ($month, $year, $daysUntilToday) {
                $startDate = Carbon::parse($leave->start_date)->startOfDay();
                $endDate = Carbon::parse($leave->end_date)->endOfDay();

                $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
                $endOfCurrentDay = Carbon::create($year, $month, $daysUntilToday)->endOfDay();

                $effectiveStart = $startDate->greaterThan($startOfMonth) ? $startDate : $startOfMonth;
                $effectiveEnd = $endDate->lessThan($endOfCurrentDay) ? $endDate : $endOfCurrentDay;

                return $carry + max(0, $effectiveStart->diffInDays($effectiveEnd) + 1);
            }, 0);

            // 缺勤天数
            $absences = max(0, $daysUntilToday - $attendances - $totalLeaveDays);

            $attendanceDays[] = $attendances;
            $leaveDays[] = $totalLeaveDays;
            $absenceDays[] = $absences;
        }

        return view('admin.report', compact(
            'staffNames',
            'staffEmails',
            'staffIds',
            'attendanceDays',
            'leaveDays',
            'absenceDays',
            'month',
            'year',
            'daysUntilToday'
        ));
    }
//------------------------------------------------------------------------------------------------------
    //Part Time Staff
    public function generateQR()
    {
        // 生成供 Part-Time 员工扫描的二维码数据
        $qrCodeData = route('partTime.register'); // QR 指向的注册页面
        return view('admin.generateQR', compact('qrCodeData'));
    }

    public function viewPartTimeStaff()
    {
        // 获取所有 Part-Time Staff 数据
        $partTimeStaff = PartTimeStaff::select('name', 'email', 'phone', 'created_at as submitted_at')->get();

        // 将数据传递到视图
        return view('admin.partTimeStaff', compact('partTimeStaff'));
    }

    public function viewPartTimeReport()
    {
        // 获取 part_time_staff 的提交记录
        $submissionData = DB::table('part_time_staff')
            ->selectRaw('DATE(created_at) as submitted_at, name') // 提交日期和 Staff 名字
            ->get();
    
        // 分组数据
        $staffGroupedByDate = $submissionData->groupBy('submitted_at');
        $staffNames = $submissionData->pluck('name')->unique()->values(); // 获取唯一的 Staff 名字
    
        $submissionDates = $staffGroupedByDate->keys()->values(); // 提交日期（X 轴）
        $datasets = [];
        $colors = ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)'];
    
        foreach ($staffNames as $index => $staffName) {
            $staffData = [];
            foreach ($submissionDates as $date) {
                $count = $staffGroupedByDate[$date]->filter(fn($record) => $record->name === $staffName)->count();
                $staffData[] = $count; // 每个日期的 Staff 数量
            }
    
            $datasets[] = [
                'label' => $staffName,
                'data' => $staffData,
                'backgroundColor' => $colors[$index % count($colors)],
                'borderColor' => $colors[$index % count($colors)],
                'borderWidth' => 1,
            ];
        }
    
        // 获取表格数据
        $partTimeStaff = DB::table('part_time_staff')
            ->select('name', 'email', 'phone', 'referred_by', 'created_at as submitted_at')
            ->get();
    
        return view('admin.partTimeReport', compact('submissionDates', 'datasets', 'partTimeStaff'));
    }
//------------------------------------------------------------------------------------------------------
    //请假申请
    public function leaveRequests()
    {
        $leaveRequests = LeaveRequest::with('staff')->get();

        // 标记所有未读为已读
        LeaveRequest::where('is_read', false)->update(['is_read' => true]);
    
        return view('admin.leaveRequests', compact('leaveRequests')); // 返回请假申请页面
    }

    public function approveLeave($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Leave request approved.');
    }

    public function rejectLeave($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Leave request rejected.');
    }
//------------------------------------------------------------------------------------------------------
    //请假通知
    public function getNotificationCount()
    {
        $unreadCount = LeaveRequest::where('is_read', false)->count(); // 查询未读申请数量
        return response()->json(['unread_count' => $unreadCount]);
    }

    public function getUnreadLeaveRequestsCount()
    {
        $unreadCount = LeaveRequest::where('is_read', false)->count(); // 查询未读的请假申请数量
        return response()->json(['unread_count' => $unreadCount]);
    }
}