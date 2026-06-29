<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_type')->default('other');
            $table->string('title');
            $table->string('envelope_id')->nullable()->index();
            $table->string('status')->default('draft');
            $table->string('signer_name');
            $table->string('signer_email');
            $table->string('original_disk')->nullable();
            $table->string('original_path')->nullable();
            $table->string('signed_disk')->nullable();
            $table->string('signed_path')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_documents');
    }
};
