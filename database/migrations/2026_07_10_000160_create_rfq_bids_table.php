<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfq_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rfq_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->decimal('unit_price', 14, 2);
            $table->decimal('total_price', 14, 2);
            $table->integer('lead_time_days');
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->string('status')->default('submitted'); // submitted|shortlisted|accepted|rejected
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->unique(['rfq_id', 'supplier_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfq_bids');
    }
};
