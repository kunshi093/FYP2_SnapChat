<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Auth;

class AttendanceController extends Controller
{
    public function clockIn()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // 检查今天是否已经有打卡记录
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('created_at', $now->toDateString())
            ->first();

        if ($attendance) {
            return back()->with('message', 'You have already clocked in today!');
        }

        // 创建新的打卡记录
        Attendance::create([
            'user_id' => $user->id,
            'clock_in' => $now,
        ]);

        return back()->with('message', 'Clocked in successfully!');
    }

    public function clockOut()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // 查找今天的打卡记录
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('created_at', $now->toDateString())
            ->first();

        if (!$attendance) {
            return back()->with('message', 'You have not clocked in yet!');
        }

        if ($attendance->clock_out) {
            return back()->with('message', 'You have already clocked out today!');
        }

        // 更新打卡记录
        $attendance->update([
            'clock_out' => $now,
        ]);

        return back()->with('message', 'Clocked out successfully!');
    }
}
