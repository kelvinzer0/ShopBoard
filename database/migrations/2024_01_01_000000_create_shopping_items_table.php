<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopping_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('problem');
            $table->text('benefit');
            $table->decimal('budget', 12, 2)->default(0);
            $table->text('purchase_link')->nullable();
            $table->enum('status', ['wishlist', 'ready', 'completed'])->default('wishlist');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopping_items');
    }
};
