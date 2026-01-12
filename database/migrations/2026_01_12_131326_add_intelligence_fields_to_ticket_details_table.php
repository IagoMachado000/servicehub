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
        Schema::table('ticket_details', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_details', 'processed_data')) {
                $table->dropColumn('processed_data');
            }

            $table->text('summary')->nullable()->after('description');
            $table->json('keywords')->nullable()->after('summary');
            $table->decimal('sentiment_score', 5, 2)->default(0)->after('keywords');
            $table->timestamp('processed_at')->nullable()->after('sentiment_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_details', function (Blueprint $table) {
            Schema::table('ticket_details', function (Blueprint $table) {
                $table->dropColumn(['summary', 'keywords', 'sentiment_score', 'processed_at']);
                $table->json('processed_data')->nullable();
            });
        });
    }
};
