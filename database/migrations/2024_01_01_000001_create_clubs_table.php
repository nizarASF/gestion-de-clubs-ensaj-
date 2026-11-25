<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clubs', function (Blueprint $t) {
            $t->id();
            $t->string('name', 100);
            $t->string('slug', 120)->unique();
            $t->string('logo_path')->nullable();
            $t->string('short_description', 300);
            $t->text('description');
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
