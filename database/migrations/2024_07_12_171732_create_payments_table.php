<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Reference to the user who made the payment
            $table->unsignedBigInteger('order_id');
            $table->decimal('amount', 10, 2); // Amount paid
            $table->string('currency', 3); // Currency code (e.g., USD)
            $table->string('payment_method'); // Payment method (e.g., 'stripe', 'paypal')
            $table->string('payment_status'); // Status of the payment (e.g., 'succeeded', 'failed')
            $table->string('transaction_id')->nullable(); // Transaction ID from the payment gateway
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();
            
            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
