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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title') ;  
            $table->longText('description') ; 
            $table->text('small_description')->nullable() ; 
            $table->string('slug')->unique() ; 
            $table->bigInteger('number_of_views')->default(0);
            $table->boolean('comment_able')->default(1);
            $table->boolean('status')->default(1);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            //$table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
