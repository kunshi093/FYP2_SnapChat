<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PartTimeStaff;

class partTimeController extends Controller
{
    // 显示注册表单
    public function showRegisterForm()
    {
        return view('partTime.register'); // 确保视图文件存在
    }

    // 处理注册请求
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:part_time_staff,email',
            'phone' => 'required|regex:/^[0-9]{10,15}$/',
            'referred_by' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        PartTimeStaff::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'referred_by' => $validatedData['referred_by'],
            'position' => $validatedData['position'],
            'registered_at' => now(),
        ]);

        return redirect()->route('partTime.register')->with('success', 'Registration successful.');
    }
}
