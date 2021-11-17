<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('username', 20)->nullable()->unique('username');
            $table->char('password')->nullable();
            $table->decimal('funds', 7)->nullable()->default(0);
            $table->enum('payment_terms', ['invoice', 'prepaid'])->nullable()->default('invoice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
