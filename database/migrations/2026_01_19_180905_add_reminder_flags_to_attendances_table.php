<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->boolean('clock_in_reminder_sent')->default(0)->after('status');
            $table->boolean('clock_out_reminder_sent')->default(0)->after('clock_in_reminder_sent');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'clock_in_reminder_sent',
                'clock_out_reminder_sent',
            ]);
        });
    }
};
