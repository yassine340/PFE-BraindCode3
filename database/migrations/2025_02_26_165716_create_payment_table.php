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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('formation_id')->constrained()->onDelete('cascade');
            $table->string('Pays');
            $table->string('ville');
            $table->string('adresse');
            $table->string('code_postal');
            $table->decimal('amount', 10, 2); // Changed from 'montant' to match controller
            $table->string('currency'); // Added to match controller
            $table->string('stripe_payment_intent_id')->nullable();
            $table->decimal('coach_amount', 10, 2)->nullable()->after('amount');
            // Add PayPal specific fields
            $table->string('paypal_order_id')->nullable();
            $table->string('paypal_capture_id')->nullable();
            
            // Add payment_method field to distinguish between payment providers
            $table->string('payment_method')->default('stripe');
            $table->string('status');
            $table->timestamps();
        });
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Remove the columns added
            $table->dropColumn('paypal_order_id');
            $table->dropColumn('paypal_capture_id');
            $table->dropColumn('payment_method');
            $table->dropColumn('coach_amount');
            
            // Revert stripe_payment_intent_id to non-nullable
            $table->string('stripe_payment_intent_id')->nullable(false)->change();
        });
    }
};