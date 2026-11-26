<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('special_closures', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('title', 150);
            $table->text('reason')->nullable();
            $table->boolean('is_closed')->default(true);
            $table->time('custom_open_time')->nullable();
            $table->time('custom_close_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_closures');
    }
};
