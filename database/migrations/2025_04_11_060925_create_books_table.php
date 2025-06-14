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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', );
            $table->string('author', );
            $table->string('number_book', );
            $table->string('publisher',);
            $table->integer('publication_year');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->integer('stock');
            $table->string('slug')->unique();
            $table->text('cover');
            $table->timestamps();
            
            // Foreign key constraint jika ada tabel categories
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};