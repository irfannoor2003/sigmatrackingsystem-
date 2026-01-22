<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->integer('distance_meters')->nullable()->after('lng');
            $table->boolean('qr_verified')->default(false)->after('office_verified');
            $table->enum('checkin_method', ['gps', 'qr'])->nullable()->after('qr_verified');
            $table->string('device_hash', 255)->nullable()->after('checkin_method');
            $table->string('checkin_ip', 50)->nullable()->after('device_hash');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'distance_meters',
                'qr_verified',
                'checkin_method',
                'device_hash',
                'checkin_ip'
            ]);
        });
    }
};
