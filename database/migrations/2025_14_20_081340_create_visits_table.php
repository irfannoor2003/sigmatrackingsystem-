<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
   Schema::create('visits', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('customer_id');
    $table->unsignedBigInteger('salesman_id');
    $table->string('purpose')->nullable();
    $table->text('notes')->nullable();
    $table->string('status')->default('pending');
    $table->timestamp('started_at')->nullable();   // <-- ADD THIS
    $table->timestamp('completed_at')->nullable(); // optional for completion
    $table->timestamps();

    $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
    $table->foreign('salesman_id')->references('id')->on('users')->onDelete('cascade');
});

}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
