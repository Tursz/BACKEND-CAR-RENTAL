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
            $table->UnsignedBigInteger('brand_id');
            $table->UnsignedBigInteger('color_id');
            $table->UnsignedBigInteger('type_id');
            $table->string('name');
            $table->string('plate');
            $table->double('km')->default(0);
            $table->string('chassi');
            $table->enum('is_active',['active', 'inactive'])->default('active');
            $table->integer('year');
            $table->enum('is_available',['available', 'not_available'])->default('available');
            $table->double('price');
            $table->softDeletes('deleted_at');
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('color_id')->references('id')->on('colors');
            $table->foreign('type_id')->references('id')->on('types');
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
