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
        Schema::create('role', function (Blueprint $table) {
            $table->id(); // Chiave primaria auto-increment
            // ... altre colonne ...
             $table->string('role');
            // Aggiungi le colonne user_id e admin_id come chiavi esterne
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('admin_id');
    
           
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role');
    }
};
 