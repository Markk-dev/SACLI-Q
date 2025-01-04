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
            $table->string('name');
            $table->string('account_id')->unique();
            $table->string('access_type')->default('user');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status')->default('open');
            $table->timestamps();
        });
        
        Schema::create('window_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('status')->default('open');
            $table->foreignId('queue_id')->constrained('queues')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('window_group_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('window_group_id')->constrained('window_groups')->onDelete('cascade');            
            $table->string('window_name')->nullable(); 
            $table->timestamps();
        });

        Schema::create('queued', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->string('status')->default('Waiting');
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('window_group_id')->constrained('window_groups')->onDelete('cascade');
            $table->foreignId('queue_id')->constrained('queues')->onDelete('cascade');
            $table->timestamp('called_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
