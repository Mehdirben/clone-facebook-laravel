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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // L'utilisateur qui reçoit la notification
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade'); // L'utilisateur qui a causé la notification
            $table->string('type'); // like, comment, friend_request, etc.
            $table->morphs('notifiable'); // Le modèle qui a causé la notification (post, comment, etc.)
            $table->text('content')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
