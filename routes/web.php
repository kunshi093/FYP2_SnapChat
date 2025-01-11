<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PartTimeController;
use Illuminate\Support\Facades\Auth;

// 默认首页路由
Route::get('/', function () {
    return view('welcome');
});

// 身份验证路由
Auth::routes();
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Admin 路由组
Route::middleware(['auth', 'admin'])->group(function () {
    //主页
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    //staff
    Route::get('/admin/staff', [AdminController::class, 'viewStaff'])->name('admin.viewStaff');
    Route::get('/admin/staff/{id}/profile', [AdminController::class, 'viewStaffProfile'])->name('admin.viewStaffProfile');
    Route::get('/admin/staff/{id}/attendance', [AdminController::class, 'viewStaffAttendance'])->name('admin.viewStaffAttendance');
    Route::get('/admin/staff/{id}/edit', [AdminController::class, 'editStaff'])->name('admin.editStaff');
    Route::put('/admin/staff/{id}', [AdminController::class, 'updateStaff'])->name('admin.updateStaff');
    Route::delete('/admin/staff/{id}', [AdminController::class, 'deleteStaff'])->name('admin.deleteStaff');
    Route::post('/staff/{id}/upload-photo', [StaffController::class, 'uploadPhoto'])->name('staff.uploadPhoto');
    //看出席记录
    Route::get('/admin/reports', [AdminController::class, 'report'])->name('admin.reports');
    //请假申请
    Route::get('/admin/leave-requests', [AdminController::class, 'leaveRequests'])->name('admin.leaveRequests');
    Route::post('/admin/approve-leave/{id}', [AdminController::class, 'approveLeave'])->name('admin.approveLeave');
    Route::post('/admin/reject-leave/{id}', [AdminController::class, 'rejectLeave'])->name('admin.rejectLeave');
    // 获取未读通知数量
    Route::get('/admin/leave-requests/count', [AdminController::class, 'getUnreadLeaveRequestsCount'])->name('admin.getUnreadLeaveRequestsCount');
    // 查看请假申请页面
    Route::get('/admin/leave-requests', [AdminController::class, 'leaveRequests'])->name('admin.leaveRequests');
    Route::get('/admin/getNotificationCount', [AdminController::class, 'getNotificationCount'])->name('admin.getNotificationCount');
    //part Time
    Route::get('/admin/part-time/generate-QR', [AdminController::class, 'generateQR'])->name('admin.generateQR');
    Route::get('/admin/partTimeStaff', [AdminController::class, 'viewPartTimeStaff'])->name('admin.partTimeStaff');
    Route::get('/admin/part-time-staff', [AdminController::class, 'viewPartTimeStaff'])->name('admin.partTimeStaff');
    Route::get('/admin/partTimeReport', [AdminController::class, 'viewPartTimeReport'])->name('admin.partTimeReport');
});
//Part time 
    Route::get('/part-time/register', [PartTimeController::class, 'showRegisterForm'])->name('partTime.register');
    Route::post('/part-time/register', [PartTimeController::class, 'register'])->name('partTime.register.submit');

// Staff 路由组
Route::middleware(['auth', 'staff'])->group(function () {
    Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
    Route::post('/staff/clock-in', [StaffController::class, 'clockIn'])->name('staff.clockIn');
    Route::post('/staff/clock-out', [StaffController::class, 'clockOut'])->name('staff.clockOut');
    Route::post('/staff/clock-in/{id}/upload-photo', [StaffController::class, 'uploadPhoto'])->name('staff.uploadPhoto');
    Route::post('/staff/clock-in/{id}/upload-photo', [StaffController::class, 'uploadPhoto'])->name('staff.uploadPhoto');
    Route::get('/staff/profile', [StaffController::class, 'profile'])->name('staff.profile');
    Route::get('/staff/profile/edit', [StaffController::class, 'editProfile'])->name('staff.editProfile');
    Route::post('/staff/profile/update', [StaffController::class, 'updateProfile'])->name('staff.updateProfile');
    Route::put('/staff/profile/update', [StaffController::class, 'updateProfile'])->name('staff.updateProfile');
    Route::get('/staff/leave-application', [StaffController::class, 'leaveApplication'])->name('staff.leaveApplication');
    Route::post('/staff/submit-leave', [StaffController::class, 'submitLeave'])->name('staff.submitLeave');
    Route::get('/staff/leave-history', [StaffController::class, 'leaveHistory'])->name('staff.leaveHistory');
    Route::get('/staff/calendar', [StaffController::class, 'calendar'])->name('staff.calendar');
});