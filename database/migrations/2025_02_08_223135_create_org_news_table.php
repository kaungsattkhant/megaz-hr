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
        Schema::create('org_news', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time');
            $table->longText('title');
            $table->longText('description');
            $table->foreignId('created_by');
            $table->string('org_news_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_news');
    }
};
