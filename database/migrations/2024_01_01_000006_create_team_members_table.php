<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $t) {
            $t->id();
            $t->foreignId('club_id')->constrained()->cascadeOnDelete();
            $t->foreignId('proposed_by')->constrained('users')->cascadeOnDelete();
            $t->string('name', 150);
            $t->string('position', 100);
            $t->string('photo_path')->nullable();
            $t->string('academic_year', 9);
            $t->unsignedSmallInteger('sort_order')->default(0);
            $t->string('status', 20)->default('pending');
            $t->text('rejection_reason')->nullable();
            $t->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('validated_at')->nullable();
            $t->timestamps();
            $t->index(['club_id', 'academic_year', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
