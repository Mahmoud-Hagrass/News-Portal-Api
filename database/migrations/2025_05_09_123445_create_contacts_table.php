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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('name') ;
            $table->string('email')->unique() ;
            $table->string('subject')->default('subject') ;
            $table->string('address')->default('egypt') ; 
            $table->longText('message') ;   
            $table->string('phone')->default('') ; 
            $table->ipAddress('ip_address')->nullable();
            $table->boolean('is_read')->default(0) ; 
            $table->timestamps() ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
