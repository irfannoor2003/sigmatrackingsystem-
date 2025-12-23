<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
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

            // Timing
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();

            // Total working minutes (better than time)
            $table->integer('total_minutes')->nullable();

            // GPS
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

            // Office verification
            $table->boolean('office_verified')->default(false);

            // Optional admin note (leave reason)
            $table->string('note')->nullable();

            $table->timestamps();

            // One attendance per salesman per day
            $table->unique(['salesman_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
