<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // 外键关联 users 表
            $table->string('reason'); // 请假理由
            $table->date('start_date'); // 开始日期
            $table->date('end_date'); // 结束日期
            $table->string('photo')->nullable(); // 上传的支持文件
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // 状态
            $table->timestamps();

            // 添加外键约束
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
};
