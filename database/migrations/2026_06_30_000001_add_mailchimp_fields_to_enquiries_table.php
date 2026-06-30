<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->boolean('marketing_consent')->default(false)->after('auto_reply_sent_at');
            $table->timestamp('mailchimp_synced_at')->nullable()->after('marketing_consent');
            $table->text('mailchimp_sync_error')->nullable()->after('mailchimp_synced_at');
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn([
                'marketing_consent',
                'mailchimp_synced_at',
                'mailchimp_sync_error',
            ]);
        });
    }
};
