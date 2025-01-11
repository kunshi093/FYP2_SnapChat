<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('part_time_staff', function (Blueprint $table) {
            $table->string('phone', 15)->after('email')->nullable();
        });
    }

    public function down()
    {
        Schema::table('part_time_staff', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};