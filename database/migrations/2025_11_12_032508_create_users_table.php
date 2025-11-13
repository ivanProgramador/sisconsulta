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
         Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email', 50)->unique();
            $table->string('password', 255)->nullable();
            $table->integer('id_company')->default(0)->index();
            $table->enum('role', ['sys-admin', 'client-admin', 'client-user'])->default('client-user');
            $table->dateTime('last_login')->nullable();
            $table->dateTime('code_expiration')->nullable();
            $table->boolean('active')->default(false);
            $table->dateTime('blocked_until')->nullable();
            $table->softDeletes(); // adds deleted_at
            $table->timestamps();  // adds created_at and updated_at automatically
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
