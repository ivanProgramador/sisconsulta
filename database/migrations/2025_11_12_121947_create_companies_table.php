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
       Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name',100)->unique();
            $table->string('company_logo',255)->nullable();
            $table->string('uuid')->unique();
            $table->string('address',255)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('email',100)->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->softDeletes();   // cria deleted_at (nullable)
            $table->timestamps();    // cria created_at e updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
