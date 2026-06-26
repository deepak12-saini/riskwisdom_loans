<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone', 50);
            $table->string('email');
            $table->string('loan_type')->nullable();
            $table->string('timeline')->nullable();
            $table->string('state', 10)->nullable();
            $table->text('enquiry');
            $table->string('source')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('status')->default('new');
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamp('auto_reply_sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
