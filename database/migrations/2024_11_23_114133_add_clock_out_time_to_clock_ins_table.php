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
        Schema::table('clock_in', function (Blueprint $table) {
            // 在这里添加新的字段
            
            $table->timestamp('clock_out_time')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clock_in', function (Blueprint $table) {
            // 撤销字段
            $table->dropColumn('clock_out_time');
        });
    }
};