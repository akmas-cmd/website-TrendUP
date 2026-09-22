<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Daftar foto & video ulasan, contoh:
            // [{"type":"image","path":"/uploads/reviews/a.jpg"},{"type":"video","path":"/uploads/reviews/b.mp4"}]
            $table->json('media')->nullable()->after('comment');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('media');
        });
    }
};