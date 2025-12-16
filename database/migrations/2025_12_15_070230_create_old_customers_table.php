<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('old_customers', function (Blueprint $table) {
            $table->id();

            // Required
            $table->string('company_name');
            $table->string('contact_person');
            $table->text('address')->nullable();
            $table->string('email')->nullable();

            // Two numbers allowed in one field
            // Example: 0331-145456 / 42-37024332
            $table->string('contact')->nullable();

            $table->unsignedBigInteger('salesman_id')->nullable();

            $table->timestamps();

            // ✅ Correct composite unique (NO duplicates)
            $table->unique(
                ['company_name', 'contact_person'],
                'old_customers_company_contact_unique'
            );

            // Foreign key
            $table->foreign('salesman_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('old_customers');
    }
};
