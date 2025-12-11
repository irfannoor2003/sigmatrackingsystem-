<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('phone1');
            $table->string('phone2')->nullable();
            $table->string('email')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('industry_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();

            $table->string('image')->nullable();

            $table->unsignedBigInteger('salesman_id')->nullable();
            $table->string('address')->nullable();



            $table->timestamps();

            // Foreign key constraints
            $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete();
            $table->foreign('industry_id')->references('id')->on('industries')->nullOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->foreign('salesman_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
