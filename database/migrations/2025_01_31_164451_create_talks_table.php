<?php

use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('talks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('abstrac');
            $table->string('length')->default(TalkLength::NORMAL);
            $table->string('statis')->default(TalkStatus::SUBMITTED);
            $table->boolean('new_talk')->default(true);
            $table->foreignId('speaker_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talks');
    }
};
