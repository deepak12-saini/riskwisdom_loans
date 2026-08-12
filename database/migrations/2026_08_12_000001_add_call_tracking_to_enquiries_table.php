<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('call_status', 32)->default('new')->after('status');
            $table->text('call_notes')->nullable()->after('call_status');
            $table->timestamp('callback_at')->nullable()->after('call_notes');
            $table->timestamp('last_called_at')->nullable()->after('callback_at');
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn(['call_status', 'call_notes', 'callback_at', 'last_called_at']);
        });
    }
};
