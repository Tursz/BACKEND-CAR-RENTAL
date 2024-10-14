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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id');
            $table->foreignId('color_id');
            $table->foreignId('type_id');
            $table->string('name');
            $table->string('plate');
            $table->double('km');
            $table->string('chassi');
            $table->integer('year');
            $table->enum('is_available',['available', 'not_available'])->default('available');
            $table->double('price');
            $table->softDeletes('deleted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car');
    }
};
