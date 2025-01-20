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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Version::class);
            $table->foreignIdFor(\App\Models\App::class);
            $table->string('variant');
            $table->string('screen_dpi')->nullable();
            $table->string('sha1')->nullable();
            $table->string('sha256')->nullable();
            $table->string('md5')->nullable();
            $table->string('minimum_android')->nullable();
            $table->string('target_android')->nullable();
            $table->string('architecture')->nullable();
            $table->text('permissions')->nullable();
            $table->text('Languages')->nullable();
            $table->string('signature')->nullable();
            $table->string('file')->nullable();
            $table->string('file_size')->nullable();
            $table->string('file_type')->nullable();
            $table->date('date')->nullable();
            $table->text('whats_new')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
