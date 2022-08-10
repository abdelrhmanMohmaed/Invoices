<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('product');
            $table->foreignId('section_id')->constrained();
            $table->decimal('amount_collection', 8, 2)->nullable();
            $table->decimal('amount_commisson', 8, 2);
            $table->string('discount');
            $table->string('rate_vat');
            $table->decimal('value_vat', 8, 2);
            $table->decimal('total', 8, 2);
            $table->string('status', 50);
            $table->integer('value_status');
            $table->text('note')->nullable();
            $table->string('user');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
