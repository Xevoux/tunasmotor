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
        Schema::create('hero_settings', function (Blueprint $table) {
            $table->id();
            $table->string('deal_label')->nullable();
            $table->datetime('countdown_end_date')->nullable();
            $table->text('title');
            $table->text('description');
            $table->string('button_text')->default('Shop Now');
            $table->string('button_link')->nullable();
            $table->string('image_path');
            $table->string('image_name');
            $table->string('alt_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_settings');
    }
};
