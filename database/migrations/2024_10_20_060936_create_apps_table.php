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
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->index();
            $table->string('app_id')->unique()->index();
            $table->string('url')->unique();
            $table->string('icon')->nullable();
            $table->string('icon_url')->nullable();
            $table->string('icon_original')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->string('rating')->default(0);
            $table->string('downloads')->default(0);
            $table->string('histograms')->nullable();
            $table->string('review')->nullable();
            $table->boolean('paid')->default(false);
            $table->date('updated')->nullable();
            $table->string('trailer')->nullable();
            $table->text('whats_new')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('description')->nullable();
            $table->enum('type',['app','game'])->default('app')->index();
            $table->foreignIdFor(\App\Models\Developer::class)->nullable();
            $table->foreignIdFor(\App\Models\Category::class)->nullable();
            $table->foreignIdFor(\App\Models\User::class)->nullable();
            $table->boolean('status')->default(true)->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apps');
    }
};
