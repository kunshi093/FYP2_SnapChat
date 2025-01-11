<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('clock_ins', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('clock_out_time');
        });
    }

    public function down()
    {
        Schema::table('clock_ins', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};
