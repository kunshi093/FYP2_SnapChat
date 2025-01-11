<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClockIn; // 确保 ClockIn 模型已导入
use App\Models\LeaveRequest;
use App\Models\Leave;
use Carbon\Carbon;
class StaffController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 获取当前用户的打卡记录
        $clockInRecords = ClockIn::where('user_id', $user->id)
            ->orderBy('clock_in_time', 'desc')
            ->get();

        return view('staff.dashboard', compact('user', 'clockInRecords'));
    }
//--------------------------------------------------------------------------------------------------------------
    //主页
    public function dashboard()
    {
        $user = Auth::user();
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $today = Carbon::now(); // 获取当前日期
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        // 获取当月天数（仅包含到今天的天数）
        $daysUpToToday = $startOfMonth->diffInDays($today) + 1;

        // 获取当月所有打卡记录
        $clockInRecords = ClockIn::where('user_id', $user->id)
            ->whereYear('clock_in_time', $currentYear)
            ->whereMonth('clock_in_time', $currentMonth)
            ->get();

        // 获取当月所有批准的请假记录
        $leaveRecords = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('start_date', $currentYear)
            ->whereMonth('start_date', $currentMonth)
            ->get();

        // 计算实际的批准请假天数（仅截至今天）
        $effectiveLeaveDays = $leaveRecords->reduce(function ($carry, $leave) use ($today, $startOfMonth) {
            $start_date = Carbon::parse($leave->start_date)->startOfDay();
            $end_date = Carbon::parse($leave->end_date)->endOfDay();

            // 获取请假时间段的有效范围
            $effectiveStart = $start_date->greaterThan($startOfMonth) ? $start_date : $startOfMonth;
            $effectiveEnd = $end_date->lessThan($today) ? $end_date : $today;

            // 如果有效结束日期小于有效开始日期，跳过此请假记录
            if ($effectiveEnd->lt($effectiveStart)) {
                return $carry;
            }

            return $carry + $effectiveStart->diffInDays($effectiveEnd) + 1;
        }, 0);

        // 计算请假天数
        $totalLeaveDays = $leaveRecords->reduce(function ($carry, $leave) {
            $start_date = Carbon::parse($leave->start_date)->startOfDay();
            $end_date = Carbon::parse($leave->end_date)->endOfDay();

            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $effectiveStart = $start_date->greaterThan($startOfMonth) ? $start_date : $startOfMonth;
            $effectiveEnd = $end_date->lessThan($endOfMonth) ? $end_date : $endOfMonth;

            return $carry + $effectiveStart->diffInDays($effectiveEnd) + 1;
        }, 0);

        // 计算出勤天数
        $attendanceDays = $clockInRecords->filter(function ($record) use ($today) {
            return Carbon::parse($record->clock_in_time)->lte($today);
        })->count();

        // 计算缺勤天数
        $absenceDays = max(0, $daysUpToToday - $attendanceDays - $effectiveLeaveDays);

        // 获取当前员工的最新请假记录（用于显示在 Leave Application 卡片）
        $currentLeave = LeaveRequest::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'approved'])
                ->latest('created_at')
                ->first();

            return view('staff.dashboard', compact(
                'attendanceDays',
                'absenceDays',
                'effectiveLeaveDays',
                'clockInRecords',
                'totalLeaveDays',
                'currentLeave'
            ));
    }
//--------------------------------------------------------------------------------------------------------------
    //打进时间
    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $clockInTime = Carbon::now();

        // 查找当天的打卡记录
        $clockInRecord = ClockIn::where('user_id', $user->id)
            ->whereDate('clock_in_time', now()->toDateString())
            ->first();

        if (!$clockInRecord) {
            // 如果当天没有记录，则创建 Clock In 记录
            ClockIn::create([
                'user_id' => $user->id,
                'clock_in_time' => $clockInTime,
            ]);

            return response()->json([
                'success' => true,
                'time' => $clockInTime->format('H:i:s'), // 返回 24 小时制时间
            ]);
        }

        // 如果已有 Clock In 记录，则返回错误信息
        return response()->json([
            'success' => false,
            'message' => 'You have already clocked in for today.',
        ]);
    }
//--------------------------------------------------------------------------------------------------------------
    //打出时间
    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $clockOutTime = Carbon::now();

        // 查找当天的打卡记录
        $clockInRecord = ClockIn::where('user_id', $user->id)
            ->whereDate('clock_in_time', today())
            ->first();

        if ($clockInRecord) {
            // 检查是否已经有 Clock Out 时间
            $clockInRecord->update([
                'clock_out_time' => $clockOutTime,
            ]);

            return response()->json([
                'success' => true,
                'time' => $clockOutTime->format('h:i A'), // 返回 12 小时制时间
            ]);
        }

        // 如果没有 Clock In 记录，则返回错误信息
        return response()->json([
            'success' => false,
            'message' => 'Please clock in first before clocking out.',
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
    //上传照片
    public function uploadPhoto(Request $request, $id)
    {
        // 验证上传文件是否符合要求
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 限制图片类型和大小
        ]);

        $user = auth()->user();
        $filePath = $request->file('photo')->store('clock_in_photos', 'public');

        // 更新今天的打卡记录
        $clockInRecord = ClockIn::where('user_id', $user->id)
            ->whereDate('clock_in_time', now()->toDateString())
            ->latest('clock_in_time')
            ->first();

        if ($clockInRecord) {
            $clockInRecord->update(['photo' => $filePath]);
        }

        // 返回到之前页面，并显示成功信息
        return redirect()->back()->with('success', 'Photo uploaded successfully!');
    }
//--------------------------------------------------------------------------------------------------------------
    //个人主页
    public function profile()
    {
        $user = Auth::user(); // 获取当前登录的用户
        return view('staff.profile', compact('user'));
    }
//--------------------------------------------------------------------------------------------------------------
    //修改个人主页
    public function editProfile()
    {
        $user = Auth::user(); // 获取当前用户
        return view('staff.editProfile', compact('user')); // 渲染编辑页面
    }

    //更新资料
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:8|confirmed', // 密码验证
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('profile_photos', 'public');
            $user->photo = $filePath; // 存储新头像路径
        }

        // 如果输入了新密码
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password); // 加密并保存密码
        }

        $user->save();

        return redirect()->route('staff.profile')->with('success', 'Profile updated successfully.');
    }
//--------------------------------------------------------------------------------------------------------------
    //日历
    public function calendar()
    {
        $user = Auth::user(); // 当前员工
        $startDate = Carbon::parse($user->created_at)->startOfDay(); // 员工注册日期
        $endDate = Carbon::now()->endOfDay(); // 今天
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $dateRange = [];

        // 构建日期范围
        while ($startDate->lte($endDate)) {
            $dateRange[] = $startDate->copy();
            $startDate->addDay();
        }

        // 获取考勤记录
        $clockInRecords = ClockIn::where('user_id', $user->id)
            ->whereYear('clock_in_time', $currentYear)
            ->whereMonth('clock_in_time', $currentMonth)
            ->get()
            ->keyBy(function ($record) {
                return Carbon::parse($record->clock_in_time)->format('Y-m-d');
            });

        // 获取批准的请假记录（从 leave_requests 表中）
        // 获取当月所有批准的请假记录
        $leaveRecords = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('start_date', $currentYear)
            ->whereMonth('start_date', $currentMonth)
            ->get();

        // 构建完整的考勤数据
        $attendanceData = [];
        foreach ($dateRange as $date) {
            $formattedDate = $date->format('Y-m-d');

            // 检查是否为请假日期
            $leave = $leaveRecords->first(function ($leave) use ($date) {
                $start = Carbon::parse($leave->start_date)->startOfDay();
                $end = Carbon::parse($leave->end_date)->endOfDay();
                return $date->between($start, $end);
            });

            if ($leave) {
                $attendanceData[] = [
                    'date' => $formattedDate,
                    'status' => 'on leave', // 请假状态
                ];
            } elseif ($clockInRecords->has($formattedDate)) {
                $attendanceData[] = [
                    'date' => $formattedDate,
                    'status' => 'present', // 出勤状态
                ];
            } else {
                $attendanceData[] = [
                    'date' => $formattedDate,
                    'status' => 'absent', // 缺勤状态
                ];
            }
        }

        return view('staff.calendar', compact('user', 'attendanceData','leave','leaveRecords'));
    }
//--------------------------------------------------------------------------------------------------------------
    //显示日历
   

//--------------------------------------------------------------------------------------------------------------
    //请假申请
    public function leaveApplication()
    {
        return view('staff.leaveApplication');
    }
    //提交申请请假
    public function submitLeave(Request $request)
    {
        $request->validate([
            'reason' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $photoPath = $request->file('photo') ? $request->file('photo')->store('leave_documents', 'public') : null;

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'photo' => $photoPath,
        ]);

        return redirect()->route('staff.dashboard')->with('success', 'Leave application submitted successfully.');
    }
    //请假记录
    public function leaveHistory()
    {
        $user = Auth::user();

        // 获取当前用户所有的请假记录
        $leaveApplications = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.leaveHistory', compact('leaveApplications'));
    }

}