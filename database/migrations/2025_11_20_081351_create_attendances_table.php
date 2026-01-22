<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {

            $table->id();

            $table->foreignId('salesman_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('date')->index();

            // Attendance status
            $table->enum('status', ['present', 'leave'])
                ->default('present');

            // Time
            $table->timestamp('clock_in')->nullable();
            $table->timestamp('clock_out')->nullable();

            // Work duration (minutes)
            $table->integer('total_minutes')->nullable();

            // GPS (only for real office attendance)
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

            // Office / system flags
            $table->boolean('office_verified')->default(false); // true = office GPS
            $table->boolean('manual_visit')->default(false);    // admin marked
            $table->boolean('auto_clock_out')->default(false);  // system clock-out
            $table->boolean('short_leave')->default(false);     // after 12 / before 5

            // Admin / leave / visit note
            $table->text('note')->nullable();

            $table->timestamps();

            // One record per staff per day
            $table->unique(['salesman_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
