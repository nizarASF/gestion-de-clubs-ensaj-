<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $t) {
            $t->id();
            $t->foreignId('club_id')->constrained()->cascadeOnDelete();
            $t->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $t->string('type', 10);
            $t->string('path');
            $t->string('thumbnail_path')->nullable();
            $t->string('title', 150)->nullable();
            $t->string('mime_type', 100);
            $t->unsignedBigInteger('size_bytes');
            $t->string('status', 20)->default('pending');
            $t->text('rejection_reason')->nullable();
            $t->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('validated_at')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
